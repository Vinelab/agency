<?php namespace Agency\Caching\Types;

use Agency\Comment;
use Agency\RealTime\Content;
use Agency\RealTime\Pagination;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class SortedSet extends CachingType {

    /**
     * Add the given item to the given set in the cache.
     *
     * @param string $key
     * @param int    $score
     * @param string $items
     *
     * @return boolean
     */
    public function add($key, $score, $item)
    {
        return $this->store->zadd($this->getKey($key), $score, $item);
    }

    /**
     * Get the list of members.
     *
     * @param  string|\Agency\Content     $key
     * @param  \Agency\RealTime\Pagination $pagination
     *
     * @return array
     */
    public function get($key, Pagination $pagination)
    {
        $range = $this->getPaginationRange($pagination);

        return $this->store->zrange($this->getKey($key), $range->start(), $range->stop());
    }

    /**
     * Move the given member from a sorted set to another.
     *
     * @param  string|\Agency\Content $from
     * @param  string|\Agency\Content $to
     * @param  string|int $member
     *
     * @return bool
     */
    public function move($from, $to, $member)
    {
        $score = $this->store->zscore($this->getKey($from), $member);

        // use pipeline;
        $pipe = $this->store->pipeline();
        $this->usePipeline($pipe);

        $this->store->zrem($this->getKey($from), $member);
        $this->store->zadd($this->getKey($to), $score, $member);

        $result = $pipe->execute();

        $this->resetStore();

        return ! in_array(0, $result);
    }

    /**
     * Get the count of the members in the set of the given key.
     *
     * @param   $key
     *
     * @return int
     */
    public function count($key)
    {
        return $this->store->zcard($key);
    }
}
