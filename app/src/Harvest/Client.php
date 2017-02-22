<?php

namespace Harvest;

use DateTime;

use Guzzle\Http\Client as HttpClient;

use Harvest\Cache\CacheInterface;

class Client
{
    /**
     * HTTP client.
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * Object cache.
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param string         $url      Harvest URL.
     * @param string         $username Username.
     * @param string         $password Password.
     * @param CacheInterface $cache    Cache instance.
     */
    public function __construct($url, $username, $password, CacheInterface $cache)
    {
        $this->client = new HttpClient(
            $url,
            array(
                HttpClient::REQUEST_OPTIONS => array(
                    'headers' => array('accept' => 'application/json'),
                    'auth'    => array($username, $password),
                    'verify'  => false,
                ),
            )
        );

        $this->setCache($cache);
    }

    /**
     * Setter for the cache.
     *
     * @param CacheInterface $cache Cache instance.
     *
     * @return $this
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * Get "daily" timers.
     */
    public function getDaily(DateTime $date = null): Daily
    {
        if ($date === null) {
            $date = new DateTime();
        }

        $cacheKey = 'daily-' . $date->format('Y-m-d');

        if (!($json = $this->cache->get($cacheKey))) {
            $rawDaily = $this->fetchDaily($date);
            $json = json_decode($rawDaily, true);
            $this->cache->set($cacheKey, $json);
        }

        return new Daily($json);
    }

    /**
     * Get timer entries for a particular user between two dates.
     */
    public function getEntriesForUser(int $userId, DateTime $fromDate, DateTime $toDate, array $options = []): array
    {
        $options['from'] = $fromDate->format('Ymd');
        $options['to'] = $toDate->format('Ymd');

        $allowedOptions = ['from', 'to', 'billable'];

        $options = array_filter(
            $options,
            function ($key) use ($allowedOptions) {
                return in_array($key, $allowedOptions);
            },
            ARRAY_FILTER_USE_KEY
        );

        $options = implode(
            array_map(
                function ($k, $v) {
                    if (is_bool($v)) {
                        $v = $v ? 'yes' : 'no';
                    }
                    return sprintf('%s=%s', rawurlencode($k), rawurlencode($v));
                },
                array_keys($options),
                array_values($options)
            ),
            '&'
        );

        $cacheKey = sprintf(
            '/people/%d/entries?%s',
            $userId,
            $options
        );

        if (!($json = $this->cache->get($cacheKey))) {
            $raw = $this->client->get($cacheKey)->send()->getBody(true);
            $json = json_decode($raw, true);
            $this->cache->set($cacheKey, $json);
        }

        return array_map(
            function ($data): Entry {
                return new Entry($data['day_entry'] ?? []);
            },
            $json
        );
    }

    /**
     * Fetch the /daily endpoint.
     *
     * @return string
     */
    protected function fetchDaily(DateTime $date): string
    {
        $path = sprintf(
            '/daily/%d/%d',
            (int) $date->format('z') + 1,
            (int) $date->format('Y')
        );

        return $this->client->get($path)->send()->getBody(true);
    }
}
