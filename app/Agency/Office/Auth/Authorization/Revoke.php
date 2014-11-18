<?php namespace Agency\Office\Auth\Authorization;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Office\Auth\Authorization\Entities\Privilege;
use Agency\Contracts\Office\AuthorableInterface;

class Revoke {

    /**
     * @var Agency\Contracts\Office\AuthorableInterface
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
