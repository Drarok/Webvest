<?php

namespace Harvest\Cache;

interface CacheInterface
{

    /**
     * Store an item in the cache.
     *
     * @param string $key   Key to store the item under.
     * @param mxied  $value Value to store.
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * Get an item from the cache, or false if it doesn't exist.
     *
     * @param string $key Key of the data to fetch.
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Delete an item from the cache.
     *
     * @param string $key Key to delete.
     *
     * @return void
     */
    public function delete($key);
}
