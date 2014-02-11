<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use URL, View, Redirect, Auth, Authority, Route, App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Controller as BaseController;

use Agency\Cms\Exceptions\UnauthorizedException;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;

class Controller extends BaseController {

    /**
     * The sections repository instance.
     *
     * @var Agency\Cms\Repositories\Contracts\SectionRepositoryInterface
     */
    protected $sections;

    /**
     * Holds information about the
     * cms sections according to the
     * logged in admin.
     *
     * @var array
     */
    protected $cms_sections;

    /**
     * The permissions for the currently
     * authenticated Admin over the currently
     * visited section.
     *
     * @var Agency\Cms\Authority\PermissionsCollection
     */
    protected $admin_permissions;

    public function __construct(SectionRepositoryInterface $sections)
    {
        // make sure the user is authenticated
        // and not logging out (visiting the logout route)

        if (Auth::check() and
            Route::current()->getAction()['as'] != 'cms.logout' and
            Route::current()->getAction()['as'] != 'cms.login')
        {
            // set the sections repo
            $this->sections = $sections;
            // fill out the cms sections
            $this->cms_sections['accessible'] = $this->getAccessibleSections();
            $this->cms_sections['current'] = $this->getCurrentSection();

            // share cms sections with views
            View::share('cms_sections', $this->cms_sections);

            // set user permissions on the current section
            $this->admin_permissions = $this->getPermissions($this->cms_sections['current']);

            if ( ! count($this->admin_permissions) > 0 or ! $this->admin_permissions->has('read'))
            {
                throw new UnauthorizedException;
            }

            // share admin permissions with views
            View::share('admin_permissions', $this->admin_permissions);
        }
    }

    /**
     * Returns the section being visited.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function getCurrentSection()
    {
        $alias = $this->currentSectionAlias();

        try {

            return $this->sections->findByAlias($alias);

        } catch (ModelNotFoundException $e) { App::abort(404); }
    }

    /**
     * Returns the current section's alias
     * parsed out of the URI (URL path).
     *
     * @return string
     */
    protected function currentSectionAlias()
    {
        $url = parse_url(URL::current());
        $path = explode('/', $url['path']);

        if (isset($path[2]) and ! empty($path[2]))
        {
            return $path[2];
        }

        if (isset($path[1]) and ! empty($path[1]))
        {
            return $path[1];
        }

        return $path[0];
    }

    /**
     * Returns the sections that are allowed
     * for the authenticated admin.
     *
     * @return Agency\Cms\Authority\ResourcesCollection
     */
    protected function getAccessibleSections()
    {
        $access = Authority::access(Auth::user(), $this->sections->all());

        return $access->resources;
    }

    protected function getPermissions($section)
    {
        return Authority::permissions(Auth::user(), $section);
    }
}