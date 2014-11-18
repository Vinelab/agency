<?php namespace Agency\Office\Auth\Repositories;

use Agency\Office\Repositories\Repository;
use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\PrivilegableInterface;
use Agency\Office\Auth\Authorization\Entities\Permission;
use Agency\Contracts\Office\Repositories\PermissionRepositoryInterface;
use Agency\Contracts\Office\Repositories\PrivilegeRepositoryInterface;

class PermissionRepository extends Repository implements PermissionRepositoryInterface {

    /**
     * The privilege repository instance.
     *
     * @var Agency\Contracts\Office\Repositories\PrivilegeRepositoryInterface
     */
    protected $privileges;

    /**
     * The permission instance.
     *
     * @var Agency\Office\Auth\Authorization\Permission
     */
    protected $permission;

    public function __construct(PrivilegeRepositoryInterface $privileges, Permission $permission)
    {
        $this->model = $this->permission = $permission;

        $this->privileges = $privileges;
    }

    public function create($title, $alias, $description)
    {
        return $this->permission->create(compact('title', 'alias', 'description'));
    }

    public function update($id, $title, $alias, $description)
    {
        $permission = $this->find($id);

        $permission->fill(compact('title', 'alias', 'description'));

        $permission->save();

        return $permission;
    }

    /**
     * Get the permissions of an Authorable entity
     * over a Resource.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface   $admin
     * @param  Agency\Contracts\Office\PrivilegableInterface $resource
     * @return array
     */
    public function of(AuthorableInterface $admin, PrivilegableInterface $resource, $for_artists = null)
    {
        $privilege = $this->privileges->of($admin, $resource, $for_artists);

        if ( ! $privilege)
        {
            return [];
        }

        return $privilege->role->permissions;
    }
}
