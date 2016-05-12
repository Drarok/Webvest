<?php

namespace Harvest\Cache;

class Memory implements CacheInterface
{
    /**
     * Cached data.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key   Key to store the item under.
     * @param mxied  $value Value to store.
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get an item from the cache, or false if it doesn't exist.
     *
     * @param string $key Key of the data to fetch.
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->data[$key] ?? false;
    }

    /**
     * Delete an item from the cache.
     *
     * @param string $key Key to delete.
     *
     * @return void
     */
    public function delete($key)
    {
        unset($this->data[$key]);
    }
}
