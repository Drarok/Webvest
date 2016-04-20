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
     *
     * @param DateTime $date Optional date, defaults to today.
     *
     * @return array
     */
    public function getDaily(DateTime $date = null)
    {
        if ($date === null) {
            $date = new DateTime();
        }

        $cacheKey = 'daily-' . $date->format('Y-m-d');

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $rawDaily = $this->fetchDaily($date);

        $json = json_decode($rawDaily, true);

        $result = array();
        foreach ($json['day_entries'] as $jsonEntry) {
            $result[] = new Entry($jsonEntry);
        }

        if ($this->cache) {
            $this->cache->set($cacheKey, $result);
        }

        return $result;
    }

    /**
     * Fetch the /daily endpoint.
     *
     * @return string
     */
    protected function fetchDaily(DateTime $date)
    {
        $path = sprintf(
            '/daily/%d/%d',
            (int) $date->format('z') + 1,
            (int) $date->format('Y')
        );
        $rawDaily = $this->client->get($path)->send()->getBody(true);
        $this->cache->set('GET /daily', $rawDaily);
        return $rawDaily;
    }
}
