<?php namespace Agency\Cms\Authority;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use App;

use Illuminate\Support\Collection;
use Agency\Cms\Authority\Entities\Privilege;
use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;
use Agency\Cms\Authority\Exceptions\InvalidResourceTypeException;

class Access {

    /**
     * The accessible resources.
     *
     * @var array of Illuminate\Database\Eloquent\Collection
     */
    protected $accessible = [];

    /**
     * Create a new Access instance.
     *
     * @todo  Implement sending all privileges of an admin when no resources specified
     *
     * @param Agency\Cms\Authority\Contracts\AuthorableInterface $authorable
     * @param array $resources
     */
    public function __construct(AuthorableInterface $authorable, Collection $resources)
    {
        $query = Privilege::with('role')->orderBy('resource_id');

        foreach ($resources as $resource)
        {
            if ( ! $resource instanceof PrivilegableInterface)
            {
                throw new InvalidResourceTypeException('must implement PrivilegableInterface');
            }

            $query->orWhere(function($q) use($authorable, $resource) {
                $q->where('admin_id', $authorable->identifier());
                $q->where('resource_id', $resource->identifier());
                $q->where('resource_type', get_class($resource));
            });
        }

        $privileges = $query->get();
        // dd(\DB::getQueryLog());

        $this->accessible['resources'] = $this->getAccessibleResources($privileges);
    }

    /**
     * Collects the accessible resources for a privilege.
     *
     * @param  array of Agency\Cms\Authority\Entities\Privilege $privileges
     * @return array
     */
    protected function getAccessibleResources($privileges)
    {
        $resources = [];

        foreach($privileges as $privilege)
        {
            $instance = App::make($privilege->resource_type);
            $resource = $instance->where($instance->identifierKey(), $privilege->resource_id)
                ->first();
            $resource->role = $privilege->role;

            array_push($resources, $resource);
        }

        return new ResourcesCollection($resources);
    }

    public function __get($attr)
    {
        return isset($this->accessible[$attr]) ? $this->accessible[$attr] : null;
    }
}