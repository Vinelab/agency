<?php namespace Agency\Caching\Types;

use App;
use Config;
use Agency\Caching\Range;
use Agency\RealTime\Content;
use Agency\RealTime\Pagination;

/**
 * @category Utility - Abstract
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
abstract class CachingType
{
    /**
     * The cache store instance.
     *
     * @var \Illuminate\Cache\RedisStore
     */
    protected $store;

    /**
     * The prefix to be used when generating keys.
     *
     * @var string
     */
    protected $prefix = '';

    public function __construct()
    {
        // We will use the store instance rather than a pipeline by default.
        $this->resetStore();
    }

    /**
     * Get the range equivalent of the given $limit and $offset.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return \Fahita\Caching\Range
     */
    public function getRange($limit, $offset)
    {
        return new Range($limit, $offset);
    }

    /**
     * Get the range values (start, stop) out of the given pagination instance.
     *
     * @param  \Fahita\RealTime\Pagination $pagination
     *
     * @return \Fahita\Caching\Range
     */
    public function getPaginationRange(Pagination $pagination)
    {
        return $this->getRange($pagination->limit(), $pagination->offset());
    }

    /**
     * Get the cache key for the given content.
     *
     * @param \Fahita\RealTime\Content $content
     *
     * @return string
     */
    public function getContentKey(Content $content)
    {
        return 'content:'.$content->id();
    }

    /**
     * Get the formatted value for the given key, including the prefix.
     *
     * @param  string|\Agency\Content $key
     *
     * @return string
     */
    public function getKey($key)
    {
        if ($key instanceof Content) {
            $key = $this->getContentKey($key);
        }

        return $this->getPrefix().':'.$key;
    }

    /**
     * Set the prefix for this instance.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Get the prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get a pipeline instance.
     *
     * @return \Predis\Pipeline\PipelineContext
     */
    public function pipeline()
    {
        return $this->store->pipeline();
    }

    /**
     * Use the given pipeline to setup commands.
     *
     * @param \Predis\Pipeline\PipelineContext $pipe
     *
     * @return void
     */
    public function usePipeline($pipe)
    {
        $this->store = $pipe;
    }

    /**
     * Reset the store to default.
     *
     * @return void
     */
    public function resetStore()
    {
        $this->store = $this->getStoreInstance();
    }

    /**
     * Get a new store instance.
     *
     * @return \Illuminate\Database\Database
     */
    public function getStoreInstance()
    {
        return App::make('redis');
    }

    /**
     * Get the store instance.
     *
     * @return \Illuminate\Redis\Database|Predis\Pipeline\PipelineContext
     */
    public function getStore()
    {
        return $this->store;
    }

}
