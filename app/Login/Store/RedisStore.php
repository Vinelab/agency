<?php namespace Agency\Login\Store;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Config;

use Illuminate\Redis\Database as Redis;

class RedisStore implements StoreInterface {

    /**
     * The Redis database connection.
     *
     * @var Illumninate\Redis\Database
     */
    protected $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Add a value to the hash
     *
     * @param  string $hash The container hash that holds all the needls
     * @param  string $key
     * @param  mixed $value Value will be serialized!
     * @return void
     */
    public function put($hash, $key, $value)
    {
        return $this->redis->hset($this->hashKey($hash), $key, serialize($value));
    }

    /**
     * Fetches a value from the hash.
     *
     * @param  string $hash
     * @param  string $key
     * @return mixed Will be unserialized | false
     */
    public function get($hash, $key)
    {
        return unserialize($this->redis->hget($this->hashKey($hash), $key));
    }

    public function remove($hash, $key)
    {
        return $this->redis->hdel($this->hashKey($hash), $key);
    }

    /**
     * Casts the storage key.
     *
     * @param  string $hash
     * @return string
     */
    public function hashKey($hash)
    {
        return Config::get('cache.prefix') . ':' . $hash;
    }
}
