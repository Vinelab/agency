<?php namespace Agency\Login\Store;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use App;

use Illuminate\Cache\CacheManager as Cache;

use Agency\User;

use Vinelab\Auth\Contracts\ProfileInterface;
use Agency\Login\Store\StoreInterface;
use Agency\Login\Store\StoreManagerInterface;

use Agency\Login\Exceptions\InvalidStorageTypeException;

 /**
 * This class helps in the login process
 * by handling the caching process of an incomplete
 * profile retrieved from a social service provider.
 */
class StoreManager implements StoreManagerInterface {

    /**
     * The social service provider.
     *
     * @var string
     */
    protected $provider;

    /**
     * The Store connection.
     *
     * @var Agency\Login\StoreInterface
     */
    protected $store;

    /**
     * The profiles Stack
     *
     * @var string
     */
    protected $stack = 'auth:social:profiles';

    /**
     * The duration for which to cache a user
     * in minutes.
     *
     * @var integer
     */
    protected $user_cache_duration = 2;

    /**
     * Create a new StoreManager
     *
     * @param Agency\Login\StoreInterface $store
     */
    public function __construct(StoreInterface $store, Cache $cache)
    {
        $this->store = $store;

        $this->cache = $cache;
    }

    /**
     * Sets the social service provider value.
     *
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Returns the current value of $provider.
     *
     * @return string
     */
    public function provider()
    {
        return $this->provider;
    }

    /**
      * Return the stored User instance
      * based on the provided $token.
      *
      * @param string $token
      * @return Agency\User
      */
    public function getUser($token)
    {
        return $this->cache->get($this->decode($token));
    }

    /**
     * Stores and fetches data from cache.
     *
     * It's automatic! When no item is provided
     * the operation is to fetch the item from cache,
     * otherwise it stores the item.
     *
     * @param  string  $key
     * @param  mixed  $item
     * @param  integer $ttl
     * @return mixed
     */
    public function cache($key, $item = null, $ttl = 1)
    {
        if ( ! is_null($item))
        {
            return $this->cache->put($key, $item, $ttl);
        }

        return $this->cache->get($key);
    }

    /**
     * Adds a social profile or user to memory.
     *
     * @param Vinelab\Auth\Contracts\ProfileInterface | Agency\User $profile
     * @return  string The access token - a key to the profile gates of determination
     */
    public function put($profile)
    {
        if ($profile instanceof User)
        {
            $token = $this->generateUserToken($profile);

            $this->cache($token, $profile, $this->user_cache_duration);

        } elseif ($profile instanceof ProfileInterface) {

            $token = $this->generateProfileToken($profile);

            $this->store->put($this->stack, $token, $profile);

        } else {

            throw new InvalidStorageTypeException;
        }

        return $this->encode($token);
    }

    /**
     * Make a token expire, remove it from cache.
     *
     * @param string $token
     */
    public function expire($token)
    {
        $this->cache->forget($this->decode($token));
    }

    /**
     * Retrieves a profile from cache.
     *
     * @param  string $token
     * @return mixed
     */
    public function getProfile($token)
    {
        return $this->store->get($this->stack, $this->decode($token));
    }

    /**
     * Generates an access token for the profile.
     *
     * @param  Agency\Social\Profile $profile
     * @return string
     */
    protected function generateProfileToken(ProfileInterface $profile)
    {
        return $this->provider . md5($profile->id);
    }

    protected function generateUserToken(User $user)
    {
        return $this->provider . md5($user->id) . md5(microtime());
    }

    /**
     * Base64 encodes it.
     *
     * @param  string $it
     * @return string
     */
    protected function encode($it)
    {
        return base64_encode($it);
    }

    /**
     * Base64 decodes it.
     *
     * @param  string $it Must be Base64 encoded
     * @return string
     */
    protected function decode($it)
    {
        return base64_decode($it, true);
    }
}
