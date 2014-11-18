<?php namespace Agency\Contracts\Office;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Database\Eloquent\Model;

interface AuthInterface {

    /**
     * Instantiates a Validator instnace.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface $authorable
     *
     * @return Agency\Office\Auth\Authorization\Validator
     */
    public function allows(AuthorableInterface $authorable);

    /**
     * Instantiates a Bouncer instance.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface $authorable
     *
     * @return Agency\Office\Auth\Authorization\Bouncer
     */
    public function authorize(AuthorableInterface $authorable);

    /**
     * Instantiates an Access instance.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface $authorable
     * @param  array  $resources
     *
     * @return Agency\Office\Auth\Authorization\Access
     */
    public function access(AuthorableInterface $authorable, $resources);

     /**
     * Instantiates a Revoke instance.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface $authorable
     * @param  array $resources
     *
     * @return Agency\Office\Auth\Authorization\Revoke
     */
    public function revoke(AuthorableInterface $authorable, $resources = array(), $for_artists = null);

    /**
     * Returns the permissions of the currently logged in user
     * over a given Privilegable resource.
     *
     * @param  Agency\Contracts\Office\PrivilegableInterface $resource
     *
     * @return Agency\Office\Auth\Authorization\PermissionsCollection
     */
    public function permissions(PrivilegableInterface $resource = null, $for_artists = null);

    /**
     * Returns the permissions of an Authorable entity
     * over a given Privilegable resource.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface   $authorable
     * @param  Agency\Contracts\Office\PrivilegableInterface $resource
     *
     * @return Agency\Office\Auth\Authorization\PermissionsCollection
     */
    public function permissionsForUser(AuthorableInterface $authorable, PrivilegableInterface $resource, $for_artists = null);

}
