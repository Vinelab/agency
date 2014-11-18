<?php namespace Agency\Contracts\Office\Repositories;

use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\PrivilegableInterface;

interface PermissionRepositoryInterface {

    public function create($title, $alias, $description);

    public function update($id, $title, $alias, $description);

    public function of(AuthorableInterface $authorable, PrivilegableInterface $resource);
}
