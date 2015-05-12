<?php namespace Agency\Caching;

/**
 * This trati enables the using class to be ‘cacheable’
 * by providing methods that renders the using instance to its
 * cacheable form.
 *
 * To customize the way the instance is transformed into its "cacheable" form
 * you should override `toCacheable()`
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 *
 */
trait Cacheable
{
    /**
     * Get the key that should be used when caching this model instance.
     *
     * @return string
     */
    public function getCacheableKey()
    {
        $namespace = explode("\\", str_plural(mb_strtolower(get_class($this))));

        return end($namespace).':'.$this->getKey();
    }

    /**
     * Get the cacheable representation of this model instance.
     *
     * @return array
     */
    public function toCacheable()
    {
        return $this->toArray();
    }
}
