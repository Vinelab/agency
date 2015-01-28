<?php namespace Agency\Contracts\Cache;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Closure;

interface CacheManagerInterface {

    /**
     * put something in the cache
     *
     * @param string $key
     * @param Closure $closure
     * @param int [$duration]
     *
     * @return mixed The result from the passed closure
     */
    public function put($key, Closure $closure, $duration = null, $tags = null);

    /**
     * get an item from the cache if it exists,
     * otherwise add it by calling the Closure
     *
     * @param string $key
     * @param Closure $value define the defaut value that you'd like to add
     *                 in case there was no value in the cache
     *
     * @return mixed
     */
    public function remember($key, Closure $closure, $tags = null);

    /**
     * remove an item from the cache
     *
     * @param string $key
     *
     * @return void
     */
    public function forget($key);

    /**
     * remove item by tags from the cache
     * @param  array $tags
     * @return void
     */
    public function forgetByTags($tags);
}