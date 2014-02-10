<?php namespace Agency\Cms\Repositories\Contracts;

interface PermissionRepositoryInterface {

    public function create($title, $alias, $description);

    public function update($id, $title, $alias, $description);
}