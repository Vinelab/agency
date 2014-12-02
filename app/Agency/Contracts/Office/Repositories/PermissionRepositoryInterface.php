<?php namespace Agency\Contracts\Cms\Repositories;

use Agency\Contracts\Cms\AuthorableInterface;
use Agency\Contracts\Cms\PrivilegableInterface;

interface PermissionRepositoryInterface {

    public function create($title, $alias, $description);

    public function update($id, $title, $alias, $description);

    public function of(AuthorableInterface $authorable, PrivilegableInterface $resource);
}
