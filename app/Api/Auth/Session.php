<?php namespace Agency\Api\Auth;

use Config;
use Agency\Contracts\UserInterface;
use Illuminate\Cache\CacheManager as Cache;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Session
{
    /**
     * The prefix to user when generating cache keys.
     *
     * @var string
     */
    protected $prefix = 'auth:session';
    /**
     * The cache instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function start(UserInterface $user)
    {
        $this->cache->put($this->getCacheKey($user->getAccessToken()), $user->getKey(), Config::get('session.lifetime'));
    }

    public function extend($access_token)
    {

    }

    /**
     * Generate an access token for the given user.
     *
     * @param \Agency\Contracts\UserInterface $user
     * @param string $token
     *
     * @return string
     */
    public function generateToken(UserInterface $user)
    {
        return md5($user->getAuthIdentifier().$user->email.uniqid());
    }

    /**
     * Get the cache key for the given token.
     *
     * @param string $token
     *
     * @return string
     */
    public function getCacheKey($token)
    {
        return $this->prefix.':'.$token;
    }
}
