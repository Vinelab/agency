<?php namespace Agency\Cms\Authority;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Helper;
use Agency\Cms\Authority\Entities\Role;
use Agency\Cms\Authority\Entities\Privilege;
use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;
use Agency\Contracts\HelperInterface;

class Bouncer {

    /**
     * The authorable entity (i.e. admin)
     *
     * @var Agency\Cms\Authority\Contracts\AuthorableInterface
     */
    protected $authorable;

    /**
     * Create a new Bouncer instance.
     *
     * @param Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     */
    public function __construct(AuthorableInterface $authorable,
                                HelperInterface $helper)
    {
        $this->authorable = $authorable;
        $this->helper = $helper;
    }

    /**
     * Grant a role for an AuthorableInterface over a resource.
     *
     * @todo  Improve to accept authorization for multiple resources.
     *
     * @param  string                $role_alias The role to grant
     * @param  Agency\Cms\Authority\Contracts\PrivilegableInterface $resource
     * @return Agency\Cms\Authority\Entities\Privilege
     */
    public function grant($role_alias, PrivilegableInterface $resource)
    {
        // find role by alias
        $role = Role::where('alias', $role_alias)->first();

        if ( ! $role)
        {
            throw new Exceptions\RoleNotFoundException($role_alias);
        }

        $role_id  = $role->id;
        $admin_id = $this->authorable->identifier();

        // try finding privilege on that resource
        $privilege_found = Privilege::where('admin_id', $admin_id)
            ->where('resource_id', $resource->identifier())
            ->where('resource_type', get_class($resource))
            ->first();

        if ($privilege_found)
        {
            // the privilege exists already, update it
            $privilege_found->fill(compact('admin_id', 'role_id'));
            $privilege_found->save();
            return $privilege_found;
        }

        return $resource->privileges()->create([
            'admin_id' => $admin_id,
            'role_id'  => $role->id
        ]);
    }

    public function __call($method, $arguments)
    {
        $permission = $this->helper->aliasify($method);
        array_unshift($arguments, $permission);

        return call_user_func_array([$this, 'grant'], $arguments);
    }
}