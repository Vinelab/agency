<?php namespace Agency\Cms\Authority;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Authority\Entities\Privilege;
use Agency\Cms\Authority\Contracts\AuthorableInterface;

class Revoke {

    public function __construct(AuthorableInterface $authorable, $resources = array())
    {
        if (empty($resources))
        {
            // revoking all privileges of an admin
            $privileges = Privilege::where('admin_id', $authorable->identifier())->get();

            $this->revoke($privileges);

            return true;
        }

        $privileges = Privilege::where('admin_id', $authorable->identifier())
            ->whereIn('resource_id', $resources)->get();

        $this->revoke($privileges);

        return true;
    }

    public function revoke($privileges)
    {
        foreach ($privileges as $privilege)
        {
            $privilege->delete();
        }
    }
}