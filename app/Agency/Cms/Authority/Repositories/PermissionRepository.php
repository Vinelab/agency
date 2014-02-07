<?php namespace Agency\Cms\Authority\Repositories;

use Agency\Cms\Authority\Entities\Permission;
use Agency\Cms\Repositories\Repository;

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;

use Agency\Cms\Authority\Repositories\Contracts\PrivilegeRepositoryInterface;

class PermissionRepository extends Repository implements Contracts\PermissionRepositoryInterface {

    /**
     * The privilege repository instance.
     *
     * @var Agency\Cms\Authority\Repositories\Contracts\PrivilegeRepositoryInterface
     */
    protected $privileges;

    /**
     * The permission instance.
     *
     * @var Agency\Cms\Authority\Permission
     */
    protected $permission;

    public function __construct(PrivilegeRepositoryInterface $privileges, Permission $permission)
    {
        $this->model = $this->permission = $permission;

        $this->privileges = $privileges;
    }

    /**
     * Get the permissions of an Authorable entity
     * over a Resource.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface   $admin
     * @param  Agency\Cms\Authority\Contracts\PrivilegableInterface $resource
     * @return array
     */
    public function of(AuthorableInterface $admin, PrivilegableInterface $resource)
    {
        $privilege = $this->privileges->of($admin, $resource);

        if ( ! $privilege)
        {
            return [];
        }

        return $privilege->role->permissions;
    }
}