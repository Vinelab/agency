<?php namespace Agency\Caching\Types;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Hash extends CachingType {

    /**
     * Put an associative array as a hash map in cache.
     *
     * @param string $key
     * @param array  $hash
     *
     * @return boolean
     */
    public function put($key, array $hash)
    {
        // when the passed hash is not an array, we will check to see if we can format it into one.
        return $this->store->hmset($this->getKey($key), $hash);
    }

    /**
     * Get all the fields of the given key(s).
     *
     * @param string|array $keys
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->store->hgetall($this->getKey($key));
    }
}
