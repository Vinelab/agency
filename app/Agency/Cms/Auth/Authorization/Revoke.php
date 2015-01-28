<?php namespace Agency\Cms\Auth\Authorization;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Auth\Authorization\Entities\Privilege;
use Agency\Contracts\Cms\AuthorableInterface;

class Revoke {

    /**
     * @var Agency\Contracts\Cms\AuthorableInterface
     */
    protected $authorable;

    public function __construct(AuthorableInterface $authorable, $resources = null, $for_artists = null)
    {
        $this->authorable = $authorable;

        $access = new Access($authorable, $resources, $for_artists);

        $this->revoke($access->privileges);

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
