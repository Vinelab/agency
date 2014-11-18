<?php namespace Agency\Contracts\Office\Repositories;

use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\PrivilegableInterface;

interface PrivilegeRepositoryInterface {

    public function of(AuthorableInterface $admin, PrivilegableInterface $resource);
}
