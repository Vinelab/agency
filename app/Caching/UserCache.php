<?php namespace Agency\Caching;

use Agency\Caching\Types\Hash;
use Agency\Contracts\Caching\UserCacheInterface;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class UserCache implements UserCacheInterface {

    protected $prefix = 'user';

    /**
     * The hash caching instance.
     *
     * @var \Fahita\Caching\Types\Hash
     */
    protected $hash;

    public function __construct(Hash $hash)
    {
        $hash->setPrefix($this->prefix);
        $this->hash = $hash;
    }

    /**
     * Get the cached user data for the given id.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function get($id)
    {
        return $this->hash->get($id);
    }
}
