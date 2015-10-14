<?php namespace Agency\Login;

/**
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */

use Vinelab\Auth\Contracts\ProfileInterface;
use Agency\Login\Store\StoreManagerInterface;
use Agency\Contracts\Repositories\UserRepositoryInterface;

use Agency\Login\Exceptions\InvalidAccessTokenException;
use Agency\Login\Exceptions\UserCreationException;

class SocialAuthenticator implements SocialLoginInterface {

    /**
     * The social service provider.
     * i.e. facebook, twitter, googleplus etc.
     *
     * @var string
     */
    protected $provider;

    /**
     * The store that contains cached profiles.
     *
     * @var Agency\Login\StoreManagerInterface
     */
    protected $store;

    /**
     * Create a new social authenticator
     *
     * @param Agency\Respositories\UserRepositoryInterface $users
     * @param Agency\Login\Store\StoreManagerInterface   $store
     */
    public function __construct(UserRepositoryInterface $users,
                                StoreManagerInterface $store)
    {
        $this->users  = $users;

        $this->store = $store;
    }

    /**
     * Set the social service provider.
     *
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        // also set the provider for the store
        $this->store->setProvider($provider);
    }

    /**
     * Returns the provider.
     *
     * @return string
     */
    public function provider()
    {
        return $this->provider;
    }

    /**
     * Authenticate using a social profile
     * and return an access token to be
     * exchanged with an account.
     *
     * @param  Vinelab\Auth\Contracts\ProfileInterface $profile
     * @return string
     */
    public function authenticate(ProfileInterface $profile)
    {
        // try finding user by their profile or their email
        if ($user = $this->users->findBySocialProfile($profile) or
            $user = $this->users->findByEmail($profile->email))
        {
            // return an access token for the user profile
            return $this->store->put($user);
        }

        // user not found, must register
        return $this->register($profile);
    }

    /**
     * Register a profile.
     *
     * @param  ProfileInterface $profile
     * @return string The access token to the profile
     */
    public function register(ProfileInterface $profile)
    {
        $user = $this->users->createFromProfile($profile);

        if ($user)
        {
            return $this->store->put($user);
        }

        throw new UserCreationException;
    }

    /**
     * Return the stored User instance
     * based on the provided $token,
     * also expires the token since
     * it is only available for one-time use.
     *
     * @param string $token
     * @return Agency\User
     */
    public function getUser($token)
    {
        if ( ! $user = $this->store->getUser($token))
        {
            throw new InvalidAccessTokenException;
        }

        // expire token after retrieval
        $this->store->expire($token);

        return $user;
    }

    public function setMissingEmail($email, $token)
    {
        $profile = $this->store->getProfile($token);

        if ( ! $profile instanceof ProfileInterface)
        {
            throw new InvalidAccessTokenException;
        }

        $profile->email = $email;

        $this->store->put($profile);
    }

    /**
     * Stores the profile and returns an access token
     * for future access.
     *
     * @param  Vinelab\Auth\Contracts\ProfileInterface $profile
     * @return string
     */
    public function handleIncompleteProfile(ProfileInterface $profile)
    {
        // try finding profile
        if ($user = $this->users->findBySocialProfile($profile))
        {
            // user found, return access token
            return $this->store->put($user);
        }

        // user not found, store profile
        return $this->store->put($profile);
    }
}
