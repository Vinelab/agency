<?php namespace Agency\Cms\Authority\Repositories;

use Agency\Cms\Authority\Entities\Privilege;
use Agency\Cms\Repositories\Repository;

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;

class PrivilegeRepository extends Repository implements Contracts\PrivilegeRepositoryInterface {

    /**
     * The privilege instanace.
     *
     * @var Agency\Cms\Authority\Privilege
     */
    protected $privilege;

    public function __construct(Privilege $privilege)
    {
        $this->privilege = $this->model = $privilege;
    }

    public function of(AuthorableInterface $admin, PrivilegableInterface $resource)
    {
        return $this->privilege->with('role')
            ->where('admin_id', $admin->identifier())
            ->where('resource_id', $resource->identifier())
            ->where('resource_type', get_class($resource))
            ->first();
    }
}