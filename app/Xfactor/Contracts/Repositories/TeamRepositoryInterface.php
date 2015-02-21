<?php namespace Xfactor\Contracts\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */
use Xfactor\User;

interface TeamRepositoryInterface {

    public function createWith($title, $slug, $score, $user_count, $relations);

    public function update($id, $title, $slug,$score, $user_count,$relations);

    public function page();

    public function remove($id);

    public function join($id, User $user);

}