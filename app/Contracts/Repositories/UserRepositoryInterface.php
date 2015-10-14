<?php namespace Agency\Contracts\Repositories;

/**
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */
interface UserRepositoryInterface
{

    /**
     * Create a new user
     *
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  string $blocked
     * @return Agency\User
     */
    public function create($name, $email, $avatar, $blocked = false);

    /**
     * Update the user matched by their ID.
     *
     * @param  string $id
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     *
     * @return \Agency\User
     */
    public function update($id, $name, $email, $avatar);

    /**
     * Create a new User attaching relations to them.
     *
     * @param  string  $name
     * @param  string  $email
     * @param  string  $avatar
     * @param  boolean $blocked
     * @param  array  $relations The relations according to the Agency\User model
     * @return Agency\User
     */
    public function createWith($name, $email, $avatar,  $relations, $email_verified = false, $blocked = false);

    /**
     * Create a new user with their social account.
     *
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  array|object $profile
     * @param  array $relation
     *
     * @return \Agency\User
     */
    public function createWithSocialProvider($name, $email, $avatar, $profile, $relation);

    /**
     * Create a user with their Facebook account.
     *
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  array|object $profile
     *
     * @return \Agency\User
     */
    public function createWithFacebook($name, $email, $avatar, $profile);

    /**
     * Update user and their Facebook account.
     *
     * @param  string $id
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  array|object $profile
     *
     * @return \Agency\User
     */
    public function updateFacebook($id, $name, $email, $avatar, $profile);

    /**
     * Create a user with their Twitter account.
     *
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  array|object $profile
     *
     * @return \Agency\User
     */
    public function createWithTwitter($name, $email, $avatar, $profile);

    /**
     * Update the user and their Twitter account.
     *
     * @param  string $id
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  array|object $profile
     *
     * @return \Agency\User
     */
    public function updateTwitter($id, $name, $email, $avatar, $profile);
}
