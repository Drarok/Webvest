<?php

namespace Harvest;

use Guzzle\Http\Client as HttpClient;

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
     * @param string $url      Harvest URL.
     * @param string $username Username.
     * @param string $password Password.
     */
    public function __construct($url, $username, $password)
    {
        $this->client = new HttpClient(
            $url,
            array(
                HttpClient::REQUEST_OPTIONS => array(
                    'headers' => array('accept' => 'application/json'),
                    'auth'    => array($username, $password),
                ),
            )
        );
    }

    /**
     * Setter for the cache.
     *
     * @param Cache\CacheInterface $cache Cache instance.
     *
     * @return $this
     */
    public function setCache(Cache\CacheInterface $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * Get "daily" timers.
     *
     * @return array
     */
    public function getDaily()
    {
        $cacheKey = '/daily';

        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }

        $response = $this->client->get($cacheKey)->send();
        $json = json_decode($response->getBody(true), true);

        $result = array();
        foreach ($json['day_entries'] as $jsonEntry) {
            $result[] = new Entry($jsonEntry);
        }

        if ($this->cache) {
            $this->cache->set($cacheKey, $result);
        }

        return $result;
    }
}
