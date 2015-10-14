<?php namespace Agency\Contracts\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface SocialAccountRepositoryInterface
{
    /**
     * Find a social account with its social_id.
     *
     * @param  int|string $id
     * @param  array $relations
     *
     * @return \Agency\User|null
     */
    public function findBySocialId($id, $relations = ['user']);
}
