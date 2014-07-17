<?php

namespace Harvest;

use GuzzleHttp\Client as HttpClient;

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
        $this->client = new HttpClient([
            'base_url' => $url,
            'defaults' => [
                'headers' => ['accept' => 'application/json'],
                'auth'    => [$username, $password],
            ],
        ]);
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

        $response = $this->client->get($cacheKey);
        $json = json_decode($response->getBody(true), true);

        $result = [];
        foreach ($json['day_entries'] as $jsonEntry) {
            $result[] = new Entry($jsonEntry);
        }

        if ($this->cache) {
            $this->cache->set($cacheKey, $result);
        }

        return $result;
    }
}
