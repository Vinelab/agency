<?php namespace Agency\Cms\Auth;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 *
 * @todo  Update privilegable and authorable implementations to use Laravel's built-in methods
 *        getKeyName and getKey instead of identifier
 */

use App;
use Which;
use Illuminate\Database\Eloquent\Model;
use Agency\Cms\Auth\Authorization\Clear;
use Agency\Cms\Auth\Authorization\Access;
use Agency\Cms\Auth\Authorization\Revoke;
use Agency\Cms\Auth\Authorization\Bouncer;
use Agency\Cms\Auth\Authorization\Validator;
use Agency\Contracts\Cms\AuthInterface;
use Illuminate\Auth\Guard as IlluminateGuard;
use Agency\Contracts\Cms\AuthorableInterface;
use Agency\Contracts\Cms\PrivilegableInterface;
use Agency\Cms\Auth\Authorization\PermissionsCollection;
use Agency\Contracts\Cms\Repositories\PermissionRepositoryInterface;

class Guard extends IlluminateGuard implements AuthInterface {

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $permissions;

    /**
     * Instantiates a Validator instnace.
     *
     * @param Agency\Contracts\Cms\AuthorableInterface $authorable
     *
     * @return Agency\Cms\Auth\Authorization\Validator
     */
    public function allows(AuthorableInterface $authorable)
    {
        return new Validator($authorable);
    }

    /**
     * Instantiates a Bouncer instance.
     *
     * @param Agency\Contracts\Cms\AuthorableInterface $authorable
     *
     * @return Agency\Cms\Auth\Authorization\Bouncer
     */
    public function authorize(AuthorableInterface $authorable)
    {
        return new Bouncer($authorable);
    }

    /**
     * Instantiates an Access instance.
     *
     * @param Agency\Contracts\Cms\AuthorableInterface $authorable
     * @param array                                      $resources
     * @param bool                                       $for_artists
     *
     * @return Agency\Cms\Auth\Authorization\Access
     */
    public function access(AuthorableInterface $authorable, $resources = null, $for_artists = null)
    {
        return new Access($authorable, $resources, $for_artists);
    }

    /**
     * Instantiates a Revoke instance.
     *
     * @param Agency\Contracts\Cms\AuthorableInterface $authorable
     * @param array                                      $resources
     * @param bool                                       $for_artists
     *
     * @return Agency\Cms\Auth\Authorization\Revoke
     */
    public function revoke(AuthorableInterface $authorable, $resources = null, $for_artists = null)
    {
        return new Revoke($authorable, $resources, $for_artists);
    }

    /**
     * Clear privileges by resource type.
     *
     * @param Agency\Contracts\Cms\AuthorableInterface $authorable The admin instance
     * @param array                                      $resources  Must be an array of
     *                                                               PrivilegableInterface instances
     *
     * @return Agency\Cms\Auth\Authorization\Clear
     */
    public function clear(AuthorableInterface $authorable, $resources)
    {
        return new Clear($authorable, $resources);
    }

    /**
     * Returns the permissions of the currently logged in user
     * over a given Privilegable resource.
     *
     * @param Agency\Contracts\Cms\PrivilegableInterface $resource
     * @param bool                                         $for_artists
     *
     * @return Agency\Cms\Auth\Authorization\PermissionsCollection
     */
    public function permissions(PrivilegableInterface $resource = null, $for_artists = null)
    {
        // First, we check for existing permissions and return them if found.
        if ($this->permissions) {
            return $this->permissions;
        }

        // By default we will fetch the permissions for the current
        // section being visited.
        if (! $resource) {
            $resource = Which::section();
        }

        // we will initially use an empty permissions collection, if there's a user logged in
        // it will be filled with horror... I mean permissions.
        $permissions = new PermissionsCollection();

        if ($user = $this->user()) {
            $permissions = $this->permissionsForUser($user, $resource, $for_artists);
        }

        // Set the permissions for this Guard instance so that we
        // skip fetching them again when needed.
        $this->setPermissions($permissions);

        return $permissions;
    }

    /**
     * Get the accessible sections by the logged in user.
     *
     * @return \Agency\Cms\Auth\Authorization\ResourcesCollection
     */
    public function accessibleSections()
    {
        $sections = $this->getSectionsRepository()->all();

        return (new Access($this->user(), $sections))->resources;
    }

    /**
     * Get a fresh instance of the section repository.
     *
     * @return \Agency\Contracts\Cms\Repositories\SectionRepositoryInterface
     */
    protected function getSectionsRepository()
    {
        return App::make('Agency\Contracts\Cms\Repositories\SectionRepositoryInterface');
    }

    /**
     * Determine whether the authenticated user has the given permission.
     *
     * @param string $permission
     * @param bool   $for_artists
     *
     * @return boolean
     */
    public function hasPermission($permission, $for_artists = null)
    {
        // If the permissions haven't been loaded yet, load them first so that
        // we can check for the required permission.
        if (! $this->permissions) {
            $this->permissions(Which::section(), $for_artists);
        }

        return $this->permissions->has($permission);
    }

    /**
     * Set the permissions for the current auth instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection $permissions
     *
     * @return void
     */
    protected function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

   /**
     * Returns the permissions of an Authorable entity
     * over a given Privilegable resource.
     *
     * @param Agency\Contracts\Cms\AuthorableInterface   $authorable
     * @param Agency\Contracts\Cms\PrivilegableInterface $resource
     * @param bool                                         $for_artists
     *
     * @return Agency\Cms\Auth\Authorization\PermissionsCollection
     */
    public function permissionsForUser(
        AuthorableInterface $authorable,
        PrivilegableInterface $resource,
        $for_artists = null
    ) {
        $permissions = App::make('Agency\Contracts\Cms\Repositories\PermissionRepositoryInterface')
            ->of($authorable, $resource, $for_artists);

        if (! is_array($permissions)) {
            $permissions = $permissions->all();
        }

        $collection = new PermissionsCollection();

        foreach ($permissions as $permission) {
            $collection->put($permission->alias, $permission);
        }

        return $collection;
    }

    /**
     * Prepares the resources coming as string
     * or integer to be compatible with the instances
     * of Auth.
     *
     * @param int|string|array $resources
     *
     * @return array
     */
    public function prepareResources($resources)
    {
        if (is_null($resources)) {
            return $resources;
        }

        if (! is_array($resources)) {
            if ($resources instanceof Model) {
                $resources = [$resources->getKey()];
            } elseif (gettype($resources) === 'string' || gettype($resources) === 'integer') {
                $resources = [$resources];
            } else {
                $resource_ids = [];

                foreach ($resources as $resource) {
                    $resource_ids[] = $resource->getKey();
                }

                return $resource_ids;
            }
        }

        return $resources;
    }
}
