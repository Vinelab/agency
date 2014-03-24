<?php namespace Agency\Cms\Authority\Repositories;

use Agency\Cms\Repositories\Repository;
use Agency\Cms\Authority\Entities\Role;

class RoleRepository extends Repository implements Contracts\RoleRepositoryInterface {

    /**
     * The Role entity instance.
     *
     * @var Agency\Cms\Authority\Entities\Role
     */
    protected $role;

    public function __construct(Role $role)
    {
        $this->model = $this->role = $role;
    }
}