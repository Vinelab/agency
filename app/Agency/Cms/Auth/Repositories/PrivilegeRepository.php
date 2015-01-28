<?php namespace Agency\Cms\Auth\Repositories;

use Auth;
use Agency\Cms\Auth\Authorization\Access;
use Agency\Cms\Repositories\Repository;
use Agency\Contracts\Cms\AuthorableInterface;
use Agency\Contracts\Cms\PrivilegableInterface;
use Agency\Contracts\Cms\PrivilegeEntityInterface;
use Agency\Contracts\Cms\Repositories\PrivilegeRepositoryInterface;

class PrivilegeRepository extends Repository implements PrivilegeRepositoryInterface {

    /**
     * The privilege instanace.
     *
     * @var Agency\Cms\Auth\Authorization\Privilege
     */
    protected $privilege;

    public function __construct(PrivilegeEntityInterface $privilege)
    {
        $this->privilege = $this->model = $privilege;
    }

    /**
     * Fetch the privilege of an authorable on a given resource.
     *
     * @param  Agency\Contracts\Cms\AuthorableInterface   $admin
     * @param  PrivilegableInterface $resource
     * @return Agency\Cms\Auth\Authorization\Entities\Privilege
     */
    public function of(AuthorableInterface $admin, PrivilegableInterface $resource, $for_artists = null)
    {
        return Auth::access($admin, $resource, $for_artists)->privileges->first();
    }
}
