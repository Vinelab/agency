<?php namespace Agency\Cms\Authority\Repositories\Contracts;

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;

interface PermissionRepositoryInterface {
    public function of(AuthorableInterface $authorable, PrivilegableInterface $resource);
}