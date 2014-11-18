<?php namespace Agency\Office\Auth\Repositories;

use Auth;
use Agency\Office\Auth\Authorization\Access;
use Agency\Office\Repositories\Repository;
use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\PrivilegableInterface;
use Agency\Contracts\Office\PrivilegeEntityInterface;
use Agency\Contracts\Office\Repositories\PrivilegeRepositoryInterface;

class PrivilegeRepository extends Repository implements PrivilegeRepositoryInterface {

    /**
     * The privilege instanace.
     *
     * @var Agency\Office\Auth\Authorization\Privilege
     */
    protected $privilege;

    public function __construct(PrivilegeEntityInterface $privilege)
    {
        $this->privilege = $this->model = $privilege;
    }

    /**
     * Fetch the privilege of an authorable on a given resource.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface   $admin
     * @param  PrivilegableInterface $resource
     * @return Agency\Office\Auth\Authorization\Entities\Privilege
     */
    public function of(AuthorableInterface $admin, PrivilegableInterface $resource, $for_artists = null)
    {
        return Auth::access($admin, $resource, $for_artists)->privileges->first();
    }
}
