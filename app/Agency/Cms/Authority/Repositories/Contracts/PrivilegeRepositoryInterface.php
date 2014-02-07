<?php namespace Agency\Cms\Authority\Repositories\Contracts;

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;

interface PrivilegeRepositoryInterface {

    public function of(AuthorableInterface $admin, PrivilegableInterface $resource);
}