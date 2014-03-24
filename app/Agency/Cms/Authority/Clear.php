<?php namespace Agency\Cms\Authority;

use Agency\Cms\Authority\Entities\Privilege;
use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Authority\Contracts\PrivilegableInterface;

class Clear {

    public function __construct(AuthorableInterface $authorable, $resources)
    {
        $resource_types = $this->extractResourceTypes($resources);

        $previous = Privilege::where('admin_id', $authorable->identifier())
                        ->whereIn('resource_type', $resource_types)
                        ->delete();
    }

    protected function extractResourceTypes($resources)
    {
        $resource_types = [];

        foreach ($resources as $resource)
        {
            $type = get_class($resource);

            if ( ! in_array($type, $resource_types))
            {
                array_push($resource_types, $type);
            }
        }

        return $resource_types;
    }
}