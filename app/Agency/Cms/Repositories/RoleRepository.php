<?php namespace Agency\Cms\Repositories;

use Agency\Cms\Authority\Entities\Role;

class RoleRepository extends Repository implements Contracts\RoleRepositoryInterface {

    public function __construct(Role $role)
    {
        $this->model = $this->role = $role;
    }

    public function create($title, $alias)
    {
        return $this->role->create(compact('title', 'alias'));
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

        // detach all permissions
        $role->permissions()->sync(explode(',', $permission_ids));

        return $role;
    }

    public function allWithPermissions()
    {
        return $this->role->with('permissions')->get();
    }
}