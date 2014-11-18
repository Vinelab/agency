<?php namespace Agency\Login\Store;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\User;
use Vinelab\Auth\Contracts\ProfileInterface;

interface StoreManagerInterface {

    /**
     * Sets the social service provider value.
     *
     * @param string $provider
     */
    public function setProvider($provider);

    /**
     * Adds a social profile or user to memory.
     *
     * @param Vinelab\Auth\Contracts\ProfileInterface | Agency\User $profile
     * @return  string The access token - a key to the profile gates of determination
     */
    public function put($profile);

    /**
     * Make a token expire, remove it from cache.
     *
     * @param string $token
     */
    public function expire($token);

    /**
      * Return the stored User instance
      * based on the provided $token.
      *
      * @param string $token
      * @return Agency\User
      */
    public function getUser($token);

    /**
     * Retrieves a profile from cache.
     *
     * @param  string $token
     * @return mixed
     */
    public function getProfile($token);
}
