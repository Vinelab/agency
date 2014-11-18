<?php namespace Agency\Office\Auth\Authorization;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
use NeoEloquent;
use Agency\Helper;
use Agency\Office\Auth\Authorization\Entities\Role;
use Agency\Office\Auth\Authorization\Entities\Privilege;
use Illuminate\Database\Eloquent\Collection;
use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\PrivilegableInterface;

class Bouncer {

    /**
     * The authorable entity (i.e. admin)
     *
     * @var Agency\Contracts\Office\AuthorableInterface
     */
    protected $authorable;

    /**
     * Create a new Bouncer instance.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable
     */
    public function __construct(AuthorableInterface $authorable)
    {
        $this->authorable = $authorable;
    }

    /**
     * Grant a role for an AuthorableInterface over a resource.
     *
     * @todo  Improve to accept authorization for multiple resources.
     *
     * @param  string  $role_alias The role to grant
     * @param  mixed $resource You can send out anything that represents a resource, or the resource itself.
     * @return Agency\Office\Auth\Authorization\Entities\Privilege
     */
    public function grant($role_alias, $resources, $for_artists = null)
    {
        // Check the validity of the claimed role
        $role = Role::where('alias', $role_alias)->where('for_artists', $for_artists)->first();

        if ( ! $role)
        {
            throw new Exceptions\RoleNotFoundException($role_alias);
        }

        // In case the passed resources is not an iterable type we make it an array
        // so that we work with them in one way, not much of an overhead here anyway...
        if ( ! is_array($resources) && ! $resources instanceof Collection) $resources = [$resources];

        return $this->createAndUpdate($role, $resources, $for_artists);
    }

    /**
     * Create new privileges and update existing onces.
     *
     * @param  Agency\Office\Auth\Authorization\Access $access
     * @param  Agency\Office\Auth\Authorization\Entities\Role   $role
     * @param  mixed $resources
     * @return boolean
     */
    protected function createAndUpdate(Role $role, $resources, $for_artists)
    {
        // First we check the access of this Authorable on the given resources
        $access = new Access($this->authorable, $resources);
        $privileges = $access->privileges;

        if(count($privileges) > 0)
        {
            // Hold what needs to be updated.
            $update = [];
            // Since some (or maybe all) of the privileges were found then
            // we'll have to separate the ones we need to add form the ones
            // we are updating.
            $resource_ids = $access->resources->getIds();

            // Let's find the section ids of the found privileges then compare them
            // to the given ones and see what we get.
            foreach ($privileges as $privilege)
            {
                if (in_array($privilege->section->id, $resource_ids))
                {
                    $update[] = $privilege;
                }
            }

            // We'll only create the ones that are not supposed to be updated.
            $created = $this->create($role, array_diff($access->getResourceIds($resources), $resource_ids), $for_artists);
            $updated = $this->update($role, $update);

            return $created && $updated;

        } else
        {
            return $this->create($role, $resources, $for_artists);
        }

        // We're returning true after all since if anything wrong happends it would be thrown
        // in the middle of the process.
        return false;
    }

    /**
     * Create a new set of privileges.
     *
     * @param  Agency\Office\Auth\Authorization\Entities\Role   $role
     * @param  array  $resources
     * @return void
     */
    protected function create(Role $role, $resources, $for_artists)
    {
        $status = [];
        foreach ($resources as $resource)
        {
           $status[] = $this->createPrivilege($role, $resource, [], $for_artists);
        }

        return ! (in_array(null, $status) || in_array(false, $status));
    }

    /**
     * Update existing privileges.
     *
     * @param  Agency\Office\Auth\Authorization\Entities\Role   $role
     * @param  array  $privileges
     * @return void
     */
    protected function update(Role $role, $privileges)
    {
        $status = [];
        // Update what needs to be updated.
        foreach ($privileges as $privilege)
        {
            // A privilege for this admin already exists for this resource
            // though we are not sure that the roles are the same so we check that
            // and if they're the same we do nothing, otherwise we'll detach the role
            // from that privilege and attach the new one.
            if($privilege->role && $privilege->role->alias == $role->alias) return true;

            // Reaching here means that it's a change of roles so we need to do
            // is save the new role to the privilege and NeoEloquent will make sure
            // it gets replaced since our relation is hasOne().
            $status[] = $privilege->role()->save($role);
        }

        return ! (in_array(null, $status) || in_array(false, $status));
    }

    /**
     * Create a privilege for the authorable as $role on $resource.
     *
     * @param  Agency\Office\Auth\Authorization\Entities\Role $role
     * @param  mixed $resource Can be any reference to the resource or the resource itself.
     * @param  array $attributes
     * @return Agency\Office\Auth\Authorization\Entities\Privilege
     */
    protected function createPrivilege(Role $role, $resource, $attributes = [], $for_artists)
    {
        $section = $for_artists ? 'artistSection' : 'section';

        return Privilege::createWith($attributes, [
            'admin'   => $this->authorable->getKey(),
            'role'    => $role,
            $section  => $resource
        ]);
    }

    /**
     * Handle magic method call to allow calling a method to represent
     * the role to grant and admin with. i.e.
     *
     * Auth::authorize($admin)->admin($section);
     *     OR
     * Auth::authorize($admin)->contentManager($section);
     *
     * Then admin and contentManager will become the role granted to that admin
     * after being aliasified to 'content-manager'
     *
     * @param  string $method
     * @param  mixed $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        // Get an alias out of the role (method).
        $role = Helper::aliasify($method);
        // Call the grant method with the role alias and the first
        // element of the arguments.
        $for_artists = isset($arguments[1]) ? $arguments[1] : null;

        return $this->grant($role, $arguments[0], $for_artists);
    }
}
