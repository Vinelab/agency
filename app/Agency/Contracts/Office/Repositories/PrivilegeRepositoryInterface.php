<?php namespace Agency\Contracts\Cms\Repositories;

use Agency\Contracts\Cms\AuthorableInterface;
use Agency\Contracts\Cms\PrivilegableInterface;

interface PrivilegeRepositoryInterface {

    public function of(AuthorableInterface $admin, PrivilegableInterface $resource);
}
