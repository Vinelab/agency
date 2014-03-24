<?php namespace Agency\Cms\Repositories;

use Agency\Cms\Authority\Entities\Permission;

class PermissionRepository extends Repository implements Contracts\PermissionRepositoryInterface {

    /**
     * The Permission instance.
     *
     * @var Agency  \Cms\Authority\Entities\Permission
     */
    protected $permission;

    public function __construct(Permission $permission)
    {
        $this->model = $this->permission = $permission;
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
}