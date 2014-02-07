<?php namespace Agency\Cms\Authority;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 *
 * @todo  Update privilegable and authorable interfaces to use Laravel's built-in methods
 *        getKeyName and getKey instead of identifier
 */

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;

use Agency\Cms\Authority\Repositories\Contracts\PermissionRepositoryInterface;

class Manager implements Contracts\ManagerInterface {

    /**
     * The permissions repository instance.
     *
     * @var Agency\Cms\Repositories\Contracts\PermissionRepositoryInstance
     */
    protected $permissions;

    public function __construct(PermissionRepositoryInterface $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Instantiates a Validator instnace.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     * @return Agency\Cms\Authority\Validator
     */
    public function allows(AuthorableInterface $authorable)
    {
        return new Validator($authorable);
    }

    /**
     * Instantiates a Bouncer instance.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     * @return Agency\Cms\Authority\Bouncer
     */
    public function authorize(AuthorableInterface $authorable)
    {
        return new Bouncer($authorable);
    }

    /**
     * Instantiates an Access instance.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     * @param  array  $resources
     * @return Agency\Cms\Authority\Access
     */
    public function access(AuthorableInterface $authorable, $resources)
    {
        return new Access($authorable, $resources);
    }

    /**
     * Instantiates a Revoke instance.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     * @param  array $resources
     * @return Agency\Cms\Authority\Revoke
     */
    public function revoke(AuthorableInterface $authorable, $resources = array())
    {
        $resources = $this->prepareResources($resources);

        return new Revoke($authorable, $resources);
    }

    /**
     * Clear privileges by resource type.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     * @param  array $resources Must be an array of PrivilegableInterface instances
     * @return Agency\Cms\Authority\Clear
     */
    public function clear(AuthorableInterface $authorable, $resources)
    {
        return new Clear($authorable, $resources);
    }

    /**
     * Returns the permissions of an Authorable entity
     * over a Privilegable resource.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface   $authorable
     * @param  Agency\Cms\Authority\Contracts\PrivilegableInterface $resource
     * @return Agency\Cms\Authority\PermissionsCollection
     */
    public function permissions(AuthorableInterface $authorable, PrivilegableInterface $resource)
    {
        $permissions = $this->permissions->of($authorable, $resource);

        if ( ! is_array($permissions))
        {
            $permissions = $permissions->all();
        }

        $collection = new PermissionsCollection;

        foreach ($permissions as $permission)
        {
            $collection->put($permission->alias, $permission);
        }

        return $collection;
    }

    /**
     * Prepares the resources coming as string
     * or integer to be compatible with the instances
     * of Authority.
     *
     * @param  integer | string | array $resources
     * @return array
     */
    public function prepareResources($resources)
    {
        if ( ! is_array($resources))
        {
            if (gettype($resources) === 'string' or gettype($resources) === 'integer')
            {
                $resources = [$resources];
            } else  {

                $resource_ids = [];
                foreach ($resources as $resource)
                {
                    array_push($resource_ids, $resource->identifier());
                }

                return $resource_ids;
            }
        }

        return $resources;
    }
}