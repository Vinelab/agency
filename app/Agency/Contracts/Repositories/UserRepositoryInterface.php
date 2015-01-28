<?php namespace Agency\Contracts\Repositories;

/**
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */

use Vinelab\Auth\Contracts\ProfileInterface;

interface UserRepositoryInterface {

    /**
     * Create a new user
     *
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  string $blocked
     * @return Agency\User
     */
    public function create($name, $email, $avatar, $type, $blocked = false);

    /**
     * Create a new User attaching relations to them.
     *
     * @param  string  $name
     * @param  string  $email
     * @param  string  $avatar
     * @param  string  $type
     * @param  boolean $blocked
     * @param  array  $relations The relations according to the Agency\User model
     * @return Agency\User
     */
    public function createWith($name, $email, $avatar, $type, $blocked = false, $relations);

    /**
     * Create a user record from a social profile.
     *
     * @param  ProfileInterface $profile
     * @return Agency\User
     */
    public function createFromProfile(ProfileInterface $profile);

    /**
     * IMPORTANT!
     *
     * Must also declare an implementation
     * of the __call magic method to provide
     * a convenice for calling: findBy{attribute}
     * i.e. findByEmail, findByName, etc.
     */
    public function findBy($attribute, $value);

    public function findBySocialProfile(ProfileInterface $profile);
}
