<?php namespace Agency\Cms\Auth\Repositories;

use Agency\Cms\Repositories\Repository;
use Agency\Cms\Auth\Authorization\Entities\Role;
use Agency\Contracts\Cms\Repositories\RoleRepositoryInterface;

class RoleRepository extends Repository implements RoleRepositoryInterface {

    /**
     * The Role entity instance.
     *
     * @var Agency\Cms\Auth\Authorization\Entities\Role
     */
    protected $role;

    public function __construct(Role $role)
    {
        $this->model = $this->role = $role;
    }

    public function create($title, $alias)
    {
        return $this->role->create(compact('title', 'alias'));
    }

    public function createWithPermissions($title, $alias, $permissions)
    {
        return $this->role->createWith(compact('title', 'alias'), compact('permissions'));
    }

    public function update($id, $title, $alias)
    {
        $role = $this->find($id);

        $role->fill(compact('title', 'alias'));

        $role->save();

        return $role;
    }

    public function updatePermissions($id, $permission_ids)
    {
        $role = $this->find($id);

        // Sync permissions using ids
        $role->permissions()->sync(explode(',', $permission_ids));

        return $role;
    }

    public function allWithPermissions()
    {
        return $this->role->with('permissions')->get();
    }

}
