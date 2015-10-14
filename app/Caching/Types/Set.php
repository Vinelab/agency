<?php namespace Agency\Caching\Types;

use Agency\RealTime\Content;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Set extends CachingType {

    /**
     * Add the given items to the set matching the given key.
     *
     * @param string $key
     *
     * @param string|array $items
     */
    public function add($key, $items)
    {
        if (is_array($items)) {
            $items = implode(' ', $items);
        }

        return $this->store->sadd($this->getKey($key), $items);
    }

    /**
     * Remove an item from the set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function remove($key, $item)
    {
        return $this->store->srem($this->getKey($key), $item);
    }

    /**
     * Add the given items to the set belonging to the given content.
     *
     * @param string|array $items
     *
     * @param \Fahita\RealTime\Content $content
     */
    public function addToContent($items, Content $content)
    {
        return $this->add($this->getContentKey($content), $items);
    }

    /**
     * Get the content from cache for the given range.
     *
     * @param \Fahita\RealTime\Content $content
     *
     * @return array
     */
    public function get(Content $content)
    {
        return $this->store->smembers($this->getKey($this->getContentKey($content)));
    }

    /**
     * Get the count of the members in the set matching the given key.
     *
     * @param string $key
     *
     * @return int
     */
    public function count($key)
    {
        return $this->store->scard($this->getKey($key));
    }

    /**
     * Check whether the given item exists in the cache set of key.
     *
     * @param string $key
     * @param string $item
     *
     * @return boolean
     */
    public function exists($key, $item)
    {
        return $this->store->sismember($this->getKey($key), $item);
    }

    /**
     * Get the count of the given content's set members.
     *
     * @param \Fahita\RealTime\Content $content
     *
     * @return int
     */
    public function countForContent(Content $content)
    {
        return $this->count($this->getContentKey($content));
    }
}
