<?php namespace Agency\Cms\Authentication;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Authority\Manager as Authority;
use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface
    as AgencySectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface
    as ArtistSectionRepositoryInterface;

class AdminAuthorizer implements Contracts\AdminAuthorizerInterface {

    /**
     * This role is granted to the non-roleable
     * resources, the ones that should be available
     * to every admin.
     *
     * @var string
     */
    protected $default_role = 'admin';

    /**
     * The Agency sections repo instance.
     *
     * @var Agency\Cms\Repositories\Contracts\SectionRepositoryInterface
     */
    protected $Agency_sections;

    /**
     * The artist sections repo instance.
     *
     * @var Agency\Artists\Cms\Repositories\Contracts\SectionRepositoryInterface
     */
    protected $artist_sections;

    /**
     * The authority instance.
     *
     * @var Agency\Cms\Authority\Manager
     */
    protected $authority;

    public function __construct(AgencySectionRepositoryInterface $Agency,
                                ArtistSectionRepositoryInterface $artist,
                                Authority $authority)
    {
        $this->Agency_sections = $Agency;
        $this->artist_sections = $artist;
        $this->authority = $authority;
    }

    /**
     * Provides the initial Privilege(s) and admin
     * should be granted.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $admin
     * @param  array              $Agency
     * @param  array              $artists
     * @return void
     */
    public function initial(AuthorableInterface $admin, $Agency = [], $artists = [])
    {
        // $Agency and $artists must be in the form of ['resource alias' => 'role alias']
        $Agency_sections = $this->Agency_sections->initial(array_keys($Agency));

        $this->performAuthorization($admin, $Agency, $Agency_sections);

        // $artist_sections = $this->artists_sections->initial(array_keys($artists));

        // $this->performAuthorization($admin, $artists, $artist_sections);

        return true;
    }

    /**
     * Authorizes an AuthorableInterface instance with
     * Privileges over selected PrivilegableInterface instance(s).
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $admin
     * @param  array              $Agency
     * @param  array              $artists
     * @return void
     */
    public function authorize(AuthorableInterface $admin, $Agency = [], $artists = [])
    {
        // $Agency and $artists must be in the form of ['resource alias' => 'role alias']
        $roleable_Agency_sections = $this->Agency_sections->roleable();

        // extract matching roleable sections that have been given grants
        $Agency_sections = $roleable_Agency_sections->filter(function($section) use($Agency) {
            return array_key_exists($section->alias(), $Agency);
        });

        $this->resetAuthorization($admin, $roleable_Agency_sections);
        $this->performAuthorization($admin, $Agency, $Agency_sections);

        // $artist_sections = $this->artists_sections->initial(array_keys($artists));

        // $this->performAuthorization($admin, $artists, $artist_sections);

        return true;
    }

    /**
     * Revokes all Privileges over @param $resources.
     *
     * @param  Agency\Cms\Authority\AuthorableInterface $admin
     * @param  array              $resources
     * @return void
     */
    protected function resetAuthorization(AuthorableInterface $admin, $resources)
    {
        $this->authority->revoke($admin, $resources);
    }

    /**
     * Performs the authorization of an AuthorableInterface instance
     * according to the provided grants over resources.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $admin
     * @param  array  $granted Must be of the form ['resource alias' => 'role alias']
     * @param  array  $resources
     * @return void
     */
    protected function performAuthorization(AuthorableInterface $admin, $granted, $resources)
    {
        foreach ($resources as $resource)
        {
            $role = isset($granted[$resource->alias()]) ?
                        $granted[$resource->alias()] :
                        $this->default_role;

            // grant role
            $this->authority->authorize($admin)->$role($resource);
        }
    }
}