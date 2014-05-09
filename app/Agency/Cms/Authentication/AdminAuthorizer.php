<?php namespace Agency\Cms\Authentication;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Authority\Manager as Authority;
use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface
    as AgencySectionRepositoryInterface;

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
    protected $agency_sections;

    /**
     * The authority instance.
     *
     * @var Agency\Cms\Authority\Manager
     */
    protected $authority;

    public function __construct(AgencySectionRepositoryInterface $agency,
                                Authority $authority)
    {
        $this->agency_sections = $agency;
        $this->authority = $authority;
    }

    /**
     * Provides the initial Privilege(s) and admin
     * should be granted.
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $admin
     * @param  array              $agency
     * @return void
     */
    public function initial(AuthorableInterface $admin, $agency = [])
    {
        $agency_sections = $this->agency_sections->initial(array_keys($agency));

        $this->performAuthorization($admin, $agency, $agency_sections);

        return true;
    }

    /**
     * Authorizes an AuthorableInterface instance with
     * Privileges over selected PrivilegableInterface instance(s).
     *
     * @param  Agency\Cms\Authority\Contracts\AuthorableInterface $admin
     * @param  array              $agency
     * @return void
     */
    public function authorize(AuthorableInterface $admin, $agency = [])
    {
        $roleable_agency_sections = $this->agency_sections->roleable();

        // extract matching roleable sections that have been given grants
        $agency_sections = $roleable_agency_sections->filter(function($section) use($agency) {
            return array_key_exists($section->alias(), $agency);
        });

        $this->resetAuthorization($admin, $roleable_agency_sections);
        $this->performAuthorization($admin, $agency, $agency_sections);

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