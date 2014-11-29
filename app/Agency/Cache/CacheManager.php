<?php namespace Agency\Cache;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Closure;
use Illuminate\Cache\CacheManager as Cache;

use Agency\Contracts\Cache\CacheManagerInterface;

class CacheManager implements CacheManagerInterface {

    /**
     * @var Illuminate\Cache\CacheManager
     */
    protected $cache;

    protected $prefix = "";

    protected $tags = [];

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function put($key, Closure $closure, $duration = null, $tags = null)
    {
        $key = $this->makeKey($key);

        if(is_null($tags))
        {
            $tags = $this->tags;
        }


        if ( ! is_null($duration))
        {
            $this->cache->forget('erase_cached_post');
            return $this->cache->tags($tags)->remember($key, $duration, $closure);
        }

        return $this->cache->tags($tags)->sear($key, $closure);


    }

    public function remember($key, Closure $closure, $tags = null, $duration=null)
    {

        $cache_key = $this->makeKey($key);

        if(! is_null($duration))
        {
            if ($results = $this->cache->get($cache_key))
            {
                return $results;
            }

            return $this->put($key, $closure, $duration,$tags);
        }

        if(! is_null($tags))
        {
            $results = $this->cache->tags($tags)->get($cache_key);


            if($results)
            {
                return $results;
            }

        } else {

            if ($results = $this->cache->get($cache_key))
            {
                return $results;
            }

        }

        return $this->put($key, $closure, $duration,$tags);



    }

    public function forget($key)
    {
        return $this->cache->forget($this->makeKey($key));
    }

    public function makeKey($key)
    {
        return $this->prefix . ':' . $key;
    }

    public function getKey($key)
    {
        return $this->prefix.':'.$key;
    }

    public function get($key)
    {
        $cache_key = $this->makeKey($key);
        return $this->cache->get($cache_key);
    }

    public function forgetByTags($tags)
    {
        return $this->cache->tags($tags)->flush();
    }

    public function getTags()
    {
        return $this->tags;
    }
}
