<?php namespace Agency\Cms\Repositories\Contracts;

interface RoleRepositoryInterface {

    public function create($title, $alias);

    public function update($id, $title, $alias);

    public function updatePermissions($id, $permission_ids);

    public function allWithPermissions();

}