<?php namespace Agency\Office\Auth\Authorization;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Helper;
use Agency\Office\Auth\Authorization\Entities\Role;
use Agency\Office\Auth\Authorization\Entities\Privilege;
use Agency\Office\Auth\Authorization\Entities\Permission;

use Agency\Contracts\Office\AuthorableInterface;

class Validator {

    /**
     * The authorable entity (i.e. admin)
     *
     * @var Agency\Contracts\Office\AuthorableInterface
     */
    protected $authorable;

    /**
     * Create a new Validator instance.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable
     */
    public function __construct(AuthorableInterface $authorable)
    {
        $this->authorable = $authorable;
    }

    /**
     * Validate access to a resource through premissions
     *
     * @param  string $permission
     * @param  string $resource
     * @return boolean
     */
    public function validate($permission, $resource, $for_artists = null)
    {
        $access = new Access($this->authorable, $resource, $for_artists);
        $privilege = $access->privileges->first();

        // Fail if authorable has no privilege to the specified resource.
        if ( ! $privilege) return false;

        // Privilege exists so now we check whether the role has the requested permission.
        $permission_aliases = $privilege->role->permissions->lists('alias');

        return in_array($permission, $permission_aliases);
    }

    public function __call($method, $arguments)
    {
        $permission = Helper::aliasify($method);
        array_unshift($arguments, $permission);

        return call_user_func_array([$this, 'validate'], $arguments);
    }

}
