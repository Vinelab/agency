<?php namespace Agency\Office\Auth\Authentication;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Auth;
use Agency\Contracts\Office\AdminAuthorizerInterface;
use Agency\Contracts\Office\AuthorableInterface;
use Agency\Contracts\Office\Repositories\SectionRepositoryInterface
    as AgencySectionRepositoryInterface;

class AdminAuthorizer implements AdminAuthorizerInterface {

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
     * @var Agency\Contracts\Office\Repositories\SectionRepositoryInterface
     */
    protected $Agency_sections;

    /**
     * The artist sections repo instance.
     *
     * @var Agency\Contracts\Artists\Repositories\SectionRepositoryInterface
     */

    public function __construct(
        AgencySectionRepositoryInterface $Agency) 
    {
        $this->Agency_sections = $Agency;
    }

    /**
     * Provides the initial Privilege(s) and admin
     * should be granted.
     *
     * @param  Agency\Contracts\Office\AuthorableInterface $admin
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
     * @param  Agency\Contracts\Office\AuthorableInterface $admin
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
     * @param  Agency\Office\Auth\Authorization\AuthorableInterface $admin
     * @param  array              $resources
     * @return void
     */
    protected function resetAuthorization(AuthorableInterface $admin, $resources)
    {
        return Auth::revoke($admin, $resources);
    }

    /**
     * Performs the authorization of an AuthorableInterface instance
     * according to the provided grants over resources.
     *     $granted should be of the following format:
     *         [
     *             'section alias' => 'role alias',
     *             'section alias' => 'role alias'
     *         ]
     *
     * @param  Agency\Contracts\Office\AuthorableInterface $admin
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
            Auth::authorize($admin)->$role($resource);
        }
    }

	/**
	 * Provides the initial Privilege(s) and artist's admin
	 * should be granted.
	 *
	 * @param AuthorableInterface $admin
	 * @param array $artists
	 * @return bool
	 */
	public function artistInitial( AuthorableInterface $admin, $artists = [ ] )
	{
		$artist_sections = $this->artist_sections->initial(array_keys($artists));

		$this->performAuthorization($admin, $artists, $artist_sections);

		return true;
	}

	/**
	 * Authorizes an AuthorableInterface instance with
	 * Privileges over selected PrivilegableInterface instance(s).
	 *
	 * @param AuthorableInterface $admin
	 * @param array $artists
	 * @return bool
	 */
	public function artistAuthorize( AuthorableInterface $admin, $artists = [ ] )
	{
		$roleable_artist_sections = $this->artist_sections->roleable();

		// extract matching roleable sections that have been given grants
		$artists_sections = $roleable_artist_sections->filter(function($section) use($artists) {
			return array_key_exists($section->alias(), $artists);
		});

		$this->resetAuthorization($admin, $roleable_artist_sections);
		$this->performAuthorization($admin, $artists, $artists_sections);

		return true;
	}
}
