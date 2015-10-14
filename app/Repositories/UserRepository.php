<?php namespace Agency\Repositories;

use Carbon\Carbon;
use Agency\User;
use Agency\Contracts\Repositories\UserRepositoryInterface;

/**
 *
 * @category Repository
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * Constructor.
     *
     * @param \Agency\User $user
     */
    public function __construct(User $user)
    {
        $this->model = $this->user = $user;
    }

    /**
     * Create a new user
     *
     * @param  string $name
     * @param  string $email
     * @param  string $avatar
     * @param  string $blocked
     * @return \Agency\User
     */
    public function create($name, $email, $avatar, $email_verified = false, $blocked = false)
    {
        return $this->user->create(compact('name', 'email', 'avatar', 'email_verified', 'blocked'));
    }

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
    public function update($id, $name, $email, $avatar)
    {
        $user = $this->find($id);
        $user->update(compact('name', 'email', 'avatar'));

        return $user;
    }

    /**
     * Update the user's email.
     *
     * @param string $id
     * @param string $email
     *
     * @return bool
     */
    public function updateEmail($id, $email)
    {
        $user = $this->find($id);

        return $user->update(compact('email'));
    }

    /**
     * Verify the given email.
     *
     * @param string $email
     *
     * @return bool
     */
    public function verifyEmail($email)
    {
        $user = $this->findByEmail($email);
        $user->email_verified = true;
        $user->email_verified_at = Carbon::now();

        return $user->save();
    }

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
    public function createWith($name, $email, $avatar, $relations, $email_verified = false, $blocked = false)
    {
        return $this->user->createWith(compact('name', 'email', 'avatar', 'email_verified', 'blocked'), $relations);
    }

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
    public function createWithSocialProvider($name, $email, $avatar, $profile, $relation)
    {
        $relations = [
            $relation => (array) $profile,
        ];

        return $this->createWith($name, $email, $avatar, $relations);
    }

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
    public function createWithFacebook($name, $email, $avatar, $profile)
    {
        return $this->createWithSocialProvider($name, $email, $avatar, $profile, 'facebookAccount');
    }

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
    public function updateFacebook($id, $name, $email, $avatar, $profile)
    {
       $user = $this->update($id, $name, $email, $avatar);

        $account = $user->facebookAccount;
        $account->update((array) $profile);

        return $user;
    }

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
    public function createWithTwitter($name, $email, $avatar, $profile)
    {
        return $this->createWithSocialProvider($name, $email, $avatar, $profile, 'twitterAccount');
    }

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
    public function updateTwitter($id, $name, $email, $avatar, $profile)
    {
        $user = $this->update($id, $name, $email, $avatar);

        $account = $user->twitterAccount;
        $account->update((array) $profile);

        return $user;
    }
}
