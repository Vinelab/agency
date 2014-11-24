<?php namespace Agency\Office\Auth;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 *
 * @todo  Update privilegable and authorable implementations to use Laravel's built-in methods
 *        getKeyName and getKey instead of identifier
 */

use App;
use Which;
use Illuminate\Database\Eloquent\Model;
use Agency\Office\Auth\Authorization\Clear;
use Agency\Office\Auth\Authorization\Access;
use Agency\Office\Auth\Authorization\Revoke;
use Agency\Office\Auth\Authorization\Bouncer;
use Agency\Office\Auth\Authorization\Validator;
use Agency\Contracts\Office\AuthInterface;
use Illuminate\Auth\Guard as IlluminateGuard;
use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\PrivilegableInterface;
use Agency\Office\Auth\Authorization\PermissionsCollection;
use Agency\Contracts\Office\Repositories\PermissionRepositoryInterface;

class Guard extends IlluminateGuard implements AuthInterface {

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $permissions;

    /**
     * Instantiates a Validator instnace.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable
     *
     * @return Agency\Office\Auth\Authorization\Validator
     */
    public function allows(AuthorableInterface $authorable)
    {
        return new Validator($authorable);
    }

    /**
     * Instantiates a Bouncer instance.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable
     *
     * @return Agency\Office\Auth\Authorization\Bouncer
     */
    public function authorize(AuthorableInterface $authorable)
    {
        return new Bouncer($authorable);
    }

    /**
     * Instantiates an Access instance.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable
     * @param array                                      $resources
     * @param bool                                       $for_artists
     *
     * @return Agency\Office\Auth\Authorization\Access
     */
    public function access(AuthorableInterface $authorable, $resources = null, $for_artists = null)
    {
        return new Access($authorable, $resources, $for_artists);
    }

    /**
     * Instantiates a Revoke instance.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable
     * @param array                                      $resources
     * @param bool                                       $for_artists
     *
     * @return Agency\Office\Auth\Authorization\Revoke
     */
    public function revoke(AuthorableInterface $authorable, $resources = null, $for_artists = null)
    {
        return new Revoke($authorable, $resources, $for_artists);
    }

    /**
     * Clear privileges by resource type.
     *
     * @param Agency\Contracts\Office\AuthorableInterface $authorable The admin instance
     * @param array                                      $resources  Must be an array of
     *                                                               PrivilegableInterface instances
     *
     * @return Agency\Office\Auth\Authorization\Clear
     */
    public function clear(AuthorableInterface $authorable, $resources)
    {
        return new Clear($authorable, $resources);
    }

    /**
     * Returns the permissions of the currently logged in user
     * over a given Privilegable resource.
     *
     * @param Agency\Contracts\Office\PrivilegableInterface $resource
     * @param bool                                         $for_artists
     *
     * @return Agency\Office\Auth\Authorization\PermissionsCollection
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

        $permissions = $this->permissionsForUser($this->user(), $resource, $for_artists);

        // Set the permissions for this Guard instance so that we
        // skip fetching them again when needed.
        $this->setPermissions($permissions);

        return $permissions;
    }

    /**
     * Get the accessible sections by the logged in user.
     *
     * @return \Agency\Office\Auth\Authorization\ResourcesCollection
     */
    public function accessibleSections()
    {
        $sections = $this->getSectionsRepository()->all();

        return $this->access($this->user(), $sections)->resources;
    }

    /**
     * Get a fresh instance of the section repository.
     *
     * @return \Agency\Contracts\Office\Repositories\SectionRepositoryInterface
     */
    protected function getSectionsRepository()
    {
        return App::make('Agency\Contracts\Office\Repositories\SectionRepositoryInterface');
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
     * @param Agency\Contracts\Office\AuthorableInterface   $authorable
     * @param Agency\Contracts\Office\PrivilegableInterface $resource
     * @param bool                                         $for_artists
     *
     * @return Agency\Office\Auth\Authorization\PermissionsCollection
     */
    public function permissionsForUser(
        AuthorableInterface $authorable,
        PrivilegableInterface $resource,
        $for_artists = null
    ) {
        $permissions = App::make('Agency\Contracts\Office\Repositories\PermissionRepositoryInterface')
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
