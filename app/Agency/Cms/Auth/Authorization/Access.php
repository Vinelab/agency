<?php namespace Agency\Cms\Auth\Authorization;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use App;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Agency\Cms\Auth\Authorization\Entities\Privilege;
use Agency\Contracts\Cms\AuthorableInterface;
use Agency\Contracts\Cms\PrivilegableInterface;
use Agency\Cms\Auth\Authorization\Exceptions\InvalidResourceTypeException;

class Access {

    /**
     * What relations to fetch with the Privilege.
     *
     * @var mixed
     */
    protected $with = ['role', 'section'];

    /**
     * The accessible resources.
     *
     * @var array of Illuminate\Database\Eloquent\Collection
     */
    protected $accessible = ['resources' => [], 'privileges' => []];

    /**
     * Create a new Access instance.
     *
     * @todo  Implement sending all privileges of an admin when no resources specified
     *
     * @param Agency\Contracts\Cms\AuthorableInterface $authorable
     * @param array $resources
     */
    public function __construct(AuthorableInterface $authorable, $resources = null, $for_artists = null)
    {
        $this->initAccess($authorable, $resources, $for_artists);
    }

    protected function initAccess(AuthorableInterface $authorable, $resources, $for_artists)
    {
        // Asking for access with no resources means we need all of them for this authorable.
        if (is_null($resources))
        {
            // Set the fetched accessibles.
            $this->setAccessible($this->all($authorable));
        } else
        {
            // Set the fetched accessibles.
            $this->setAccessible($this->forResources($authorable, $this->getResourceIds($resources), $for_artists), $for_artists);
        }
    }

    /**
     * Get the ids of the given resources.
     *
     * @param  mixed $resources
     * @return array
     */
    public function getResourceIds($resources)
    {
        $resource_ids = [];
        // In case the passed resources is not an iterable type we make it an array
        // so that we work with them in one way, not much of an overhead here anyway...
        if ( ! is_array($resources) && ! $resources instanceof Collection) $resources = [$resources];

        // Collect the resource Ids out of the passed in resources so that
        // we work only with Ids moving forward.
        if ($resources instanceof Collection)
        {
            $resource_ids = $resources->lists('id');
        } else
        {
            $resource_ids = array_map(function($resource)
            {
                // We'll check to see whether we're dealing with a Model so that
                // we get its key, otherwise we'll assume it is the $id that was passed in.
                return ($resource instanceof Model) ? $resource->getKey() : $resource;
            }, $resources);
        }

        return $resource_ids;
    }

    /**
     * Get privileges for specified resources.
     *
     * @param  Agency\Contracts\Cms\AuthorableInterface $authorable
     * @param  array  $resources The resource ids.
     * @return Illuminate\Database\Eloquent\Collection
     */
    protected function forResources(AuthorableInterface $authorable, array $resources)
    {
        $section = 'section';

        return Privilege::with($this->with)
            ->whereHas('admin', function($q) use($authorable)
            {
                $q->where('id', $authorable->getKey());
            })
            ->whereHas($section, function($q) use($resources)
            {
                $q->whereIn('id', $resources);
            })->get();
    }

    /**
     * Get all the privileges of a given authorable.
     *
     * @param  Agency\Contracts\Cms\AuthorableInterface $authorable
     * @return Illuminate\Database\Eloquent\Collection
     */
    protected function all(AuthorableInterface $authorable)
    {
        return Privilege::with($this->with)
            ->whereHas('admin', function($q) use($authorable) {
                $q->where('id', $authorable->getKey());
            })->get();
    }

    /**
     * Collects the accessible resources for a privilege.
     *
     * @param  array of Agency\Cms\Auth\Authorization\Entities\Privilege $privileges
     * @return array
     */
    protected function getAccessibleResources($privileges)
    {
        $resources = [];

        foreach($privileges as $privilege)
        {
            $instance = App::make($privilege->resource_type);
            $resource = $instance->where($instance->getKeyName(), $privilege->resource_id)
                ->first();
            $resource->role = $privilege->role;

            array_push($resources, $resource);
        }

        return new ResourcesCollection($resources);
    }

    /**
     * Set the accessible components.
     *
     * @param mixed $privileges
     */
    public function setAccessible($privileges, $for_artists = null)
    {
        if ( ! empty($privileges))
        {
            $this->setAccessiblePrivileges($privileges);
            $section = 'section';
            $this->setAccessibleResources($privileges->lists($section));
        }
    }

    /**
     * Set the accessible privileges.
     *
     * @param mixed $privileges
     */
    public function setAccessiblePrivileges($privileges)
    {
        if (is_array($privileges)) $privileges = new Collection($privileges);

        $this->accessible['privileges'] = $privileges;
    }

    /**
     * Set the accessible resources.
     *
     * @param mixed $resources
     */
    public function setAccessibleResources($resources)
    {
        if (is_array($resources) || ! $resources instanceof ResourcesCollection)
        {
            $resources = new ResourcesCollection($resources);
        }

        $this->accessible['resources'] = $resources;
    }

    public function __get($attr)
    {
        return isset($this->accessible[$attr]) ? $this->accessible[$attr] : null;
    }
}
