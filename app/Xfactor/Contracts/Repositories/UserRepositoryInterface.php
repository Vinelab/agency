<?php namespace Xfactor\Contracts\Repositories;

/**
 * @author Ibrahim Fleifel <Ibrahim@vinelab.com>
 */

interface UserRepositoryInterface {


    /**
     * create a new user with a profile
     *
     * @param string $name
     * @param string $gigya_id
     * @param string $avatar
     * @param string $blocked
     * @param mixed $relation
     *
     * @return Starac\User
     */
    public function createWith( $name,
                                $gigya_id,
                                $avatar,
                                $country,
                                $relation);


    /**
     * get a collection of users by their ids
     *
     * @param array|int $ids
     * @return Collection of Xfactor\User
     */
    public function get($ids);

    /**
     * get a paginated users
     * @param  array $input associative array containing sort and order values
     * @return Illuminate\Pagination\Paginator
     */
    public function page($input);


}
