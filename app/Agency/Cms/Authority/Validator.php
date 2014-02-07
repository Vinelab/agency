<?php namespace Agency\Cms\Authority;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Helper;
use Agency\Cms\Authority\Entities\Role;
use Agency\Cms\Authority\Entities\Privilege;
use Agency\Cms\Authority\Entities\Permission;

use Agency\Cms\Authority\Contracts\AuthorableInterface;

class Validator {

    /**
     * The authorable entity (i.e. admin)
     *
     * @var Agency\Cms\Authority\Contracts\AuthorableInterface
     */
    protected $authorable;

    /**
     * Create a new Validator instance.
     *
     * @param Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     */
    public function __construct(AuthorableInterface $authorable)
    {
        $this->authorable = $authorable;
    }

    /**
     * Validate access to a resource through premissions
     *
     * @param  string $permission_alias
     * @param  string $resource
     * @return boolean
     */
    public function validate($permission_alias, $resource)
    {
        // verify permission alias
        $permission = Permission::where('alias', $permission_alias)->first();
        if ( ! $permission)
        {
            throw new Exceptions\PermissionNotFoundException($permission_alias);
        }

        $admin_id = $this->authorable->identifier();
        $resource_type = get_class($resource);

        $privilege = Privilege::where('admin_id', $admin_id)
            ->where('resource_type', $resource_type)
            ->first();

        if ( ! $privilege)
        {
            // user has no privilege to access this resource
            return false;
        }

        // privilege exists, we check to see
        // whether the role has the requested permission

        /**
         * @todo Improve this by finding the Role with
         *       its id and that the permission id
         *       exists for that role using whereHas.
         *       Finding that role confirms the validation.
         */
        $role = Role::findOrFail($privilege->role_id);
        $permissions = $role->permissions()->get();

        if ( ! count($permissions) > 0)
        {
            // this role has no permissions set
            return false;
        }

        $permissions = $permissions->toArray();

        $permission_found = array_filter($permissions,
            function($permission) use($permission_alias) {
                return $permission['alias'] == $permission_alias;
            }
        );

        // there should be only one match.
        // otherwise something must have gone wrong somewhere
        if (count($permission_found) === 1)
        {
            return true;
        }

        return false;
    }

    public function __call($method, $arguments)
    {
        $permission = Helper::aliasify($method);
        array_unshift($arguments, $permission);

        return call_user_func_array([$this, 'validate'], $arguments);
    }

}