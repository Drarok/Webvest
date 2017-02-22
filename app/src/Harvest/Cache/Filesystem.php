<?php

namespace Harvest\Cache;

class Filesystem implements CacheInterface
{
    /**
     * Pathname to save the data to.
     *
     * @var string
     */
    protected $pathname;

    /**
     * Lifetime of the cached data in seconds.
     *
     * @var int
     */
    protected $lifetime;

    /**
     * Dirty state.
     *
     * @var bool
     */
    protected $dirty = false;

    /**
     * Cached data.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor.
     *
     * @param string $pathname Pathname to load/save the data from/to.
     * @param int    $lifetime Lifetime of the cached data in seconds.
     */
    public function __construct($pathname, $lifetime = 3600)
    {
        $this->pathname = $pathname;
        $this->lifetime = $lifetime;

        $this->loadCached();
    }

    /**
     * Load cached data if it hasn't expired.
     *
     * @return void
     */
    protected function loadCached()
    {
        if (! is_readable($this->pathname)) {
            return;
        }

        if ((time() - filemtime($this->pathname)) > $this->lifetime) {
            return;
        }

        $this->data = json_decode(file_get_contents($this->pathname), true);
    }

    /**
     * Save data when destroyed.
     */
    public function __destruct()
    {
        if (! $this->dirty) {
            return;
        }

        if (is_writable($this->pathname) || is_writable(dirname($this->pathname))) {
            file_put_contents($this->pathname, json_encode($this->data));
        }
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
        $this->dirty = true;
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
        $this->dirty = true;
        unset($this->data[$key]);
    }
}
