<?php namespace Agency\RealTime\Auth;

use Agency\User;
use Illuminate\Cache\RedisStore;
use Agency\Contracts\UserInterface;
use Illuminate\Cache\Repository as Cache;
use Agency\Contracts\RealTime\AuthInterface;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Auth implements AuthInterface
{
    /**
     * The cache store instance.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * The prefix to use when generating cache keys.
     *
     * @var string
     */
    protected $cache_prefix = 'auth:session';

    /**
     * The prefix to use when generating moderator's cache keys.
     *
     * @var string
     */
    protected $moderator_cache_prefix = 'auth:moderator:session';

    /**
     * The currently authenticated user instance.
     *
     * @var \Fahita\Contracts\UserInterface
     */
    protected $user;

    /**
     * The redis instance.
     *
     * @var Illuminate\Cache\RedisStore
     */
    protected $redis;

    public function __construct(Cache $cache, RedisStore $redis)
    {
        $this->cache = $cache;
        $this->redis = $redis->connection();
    }

    /**
     * Verify the existence of the given user token.
     *
     * @param string $token
     *
     * @return \Fahita\Contracts\UserInterface|null
     */
    public function loginWithToken($token)
    {
        if ($this->verifyToken($token)) {

            $id = $this->cache->get($this->getCacheKey($token));

            $attributes = $this->redis->hgetall('users:'.$id);
            $user = (new User())->newInstance($attributes, true);
            // creating a new instance does not include the record id
            // but we want it, so we force fill it to fulfill it.
            $user->forceFill($attributes);

            return $this->login($user);
        }
    }

    /**
     * Check whether the given token belongs to a moderator.
     *
     * @param  string  $token
     *
     * @return bool
     */
    public function isModerator($token)
    {
        return $this->cache->has($this->getModeratorCacheKey($token));
    }

    /**
     * Login the given user.
     *
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return \Fahita\Contracts\UserInterface
     */
    public function login(UserInterface $user)
    {
        return $this->setUser($user);
    }

    /**
     * Get the currently authenticated user instance.
     *
     * @return \Fahita\Contracts\UserInterface
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Get the identified of the currently authenticated user.
     *
     * @return string
     */
    public function id()
    {
        return $this->user()->getKey();
    }

    public function setUser(UserInterface $user)
    {
        return $this->user = $user;
    }

    /**
     * Check whether there's a user logged in.
     *
     * @return boolean
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Verify the given token for a valid user.
     *
     * @param  string $token
     *
     * @return boolean
     */
    public function verifyToken($token)
    {
        return $this->cache->has($this->getCacheKey($token));
    }

    /**
     * Get a cache key for the given token.
     *
     * @param  string $token
     *
     * @return string
     */
    protected function getCacheKey($token)
    {
        return $this->cache_prefix.':'.$token;
    }

    /**
     * Get a moderator's cache key for the given token.
     *
     * @param  string $token
     *
     * @return string
     */
    protected function getModeratorCacheKey($token)
    {
        return $this->moderator_cache_prefix.':'.$token;
    }
}
