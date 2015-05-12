<?php namespace Agency\Mappers;

use Agency\User;
use Vinelab\Api\MappableTrait;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class UserMapper {

    use MappableTrait;

    public function map($user)
    {
        return [
            'id'           => (string) $user->getKey(),
            'name'         => (string) $user->name,
            'avatar'       => (string) $user->avatar
        ];
    }

    /**
     * Map for authentication use, which means we add the access_token
     * to the mapped user to be used for auth functionalities.
     *
     * @param array $user
     *
     * @return array
     */
    public function mapAuth($user)
    {
        $attributes = [
            'access_token'   => $user['access_token'],
            'email_verified' => (bool) $user['email_verified'],
        ];

        return array_merge($this->mapRaw($user), $attributes);
    }

    public function mapRaw(array $user)
    {
        return [
            'id'      => (string) $user['id'],
            'name'    => (string) $user['name'],
            'avatar' => (string) $user['avatar'],
        ];
    }

    /**
     * Map the given user instance for moderation.
     *
     * @param  \Agency\User   $user
     *
     * @return array
     */
    public function mapForModeration(User $user)
    {
        return [
            'id'      => (string) $user->getKey(),
            'name'    => (string) $user->name,
            'avatar'  => (string) $user->avatar,
            'email'   => (string) $user->email,
            'blocked' => (bool) $user->blocked,
        ];
    }
}
