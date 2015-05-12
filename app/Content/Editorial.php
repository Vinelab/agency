<?php namespace Agency\Content;

use Illuminate\Cache\RedisStore;
use Illuminate\Database\Eloquent\Model;

/**
 * This class is meant to manage content state in the cache.
 * Publish, Unpublish, and save content to the cache.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Editorial
{
    protected $postfix = 'published';

    public function __construct(RedisStore $redis)
    {
        $this->redis = $redis;
        $this->client = $redis->connection();
    }

    public function publish(Model $model)
    {
        return $this->client->zadd($this->getKey($model->getCacheableKey()), $model->getNormalizedPublishDate(), $model->getKey());
    }

    public function unPublish(Model $model)
    {
        return $this->client->zrem($this->getKey($model->getCacheableKey()), $model->getKey());
    }

    public function add(Model $model)
    {
        return $this->redis->forever($model->getCacheableKey().':'.$model->getKey(), $model);
    }

    public function getKey($key)
    {
        return $key.':'.$this->postfix;
    }
}
