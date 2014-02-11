<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Request, Auth, Authority, Input, Lang, Redirect;

use Agency\Cms\Validators\Contracts\AdminValidatorInterface;

use Agency\Cms\Exceptions\UnauthorizedException;
use Agency\Cms\Exceptions\InvalidAdminException;

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Repositories\Contracts\AdminRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Authentication\Contracts\AdminAuthorizerInterface;
use Agency\Cms\Notifications\Contracts\AdminRegistrationNotifierInterface;
use Agency\Cms\Authority\Repositories\Contracts\RoleRepositoryInterface;

class AdminController extends Controller {

    /**
     * The admin repository instance.
     *
     * @var Agency\Cms\Repositories\Contracts\AdminRepositoryInterface
     */
    protected $admins;

    /**
     * The role repository instance.
     *
     * @var Agency\Cms\Authority\Repositories\Contracts\RoleRepositoryInterface
     */
    protected $roles;

    /**
     * Provider of authorization related
     * tasks.
     *
     * @var Agency\Cms\Authentication\Contracts\AdminAuthorizerInterface
     */
    protected $authorizer;

    /**
     * The validator instance.
     *
     * @var Agency\Cms\Validators\AdminValidatorInterface
     */
    protected $validator;

    /**
     * The registration notifier instance.
     *
     * @var Agency\Cms\Notifiers\Contracts\AdminRegistrationNotifierInterface
     */
    protected $notifier;

    public function __construct(SectionRepositoryInterface $sections,
                                AdminRepositoryInterface $admins,
                                RoleRepositoryInterface $roles,
                                AdminAuthorizerInterface $authorizer,
                                AdminValidatorInterface $validator,
                                AdminRegistrationNotifierInterface $notifier)
    {
        parent::__construct($sections);

        $this->admins = $admins;

        $this->roles = $roles;

        $this->authorizer = $authorizer;

        $this->validator = $validator;

        $this->notifier = $notifier;
    }

    /**
     * Display a listing of the admins
     *
     * @return Response
     */
    public function index()
    {
        $admins = $this->admins->all();

        return View::make('cms.pages.administration.index', compact('admins'));
    }

    /**
     * Displays the form to create a new Admin.
     *
     * @return Response
     */
    public function create()
    {
        if ($this->admin_permissions->has('create'))
        {
            $Agency_sections   = $this->getAgencySections();
            $artists_sections = $this->getArtistSections();
            $roles = $this->getRoles();

           return View::make(
                'cms.pages.administration.create',
                compact('roles', 'Agency_sections', 'artists_sections')
            );
        }

        throw new UnauthorizedException;
    }

    /**
     * Store a new Admin.
     *
     * @return Response
     */
    public function store()
    {
        try {

            if ($this->admin_permissions->has('create'))
            {
                $this->validator->validate(Input::get('info'));

                $info = Input::get('info');

                // create admin
                $admin = $this->admins->create($info['name'], $info['email']);

                if ( ! $admin)
                {
                    return Redirect::back()->with(['errors' => [Lang::get('errors.unexpected')]])
                            ->withInput();
                }

                // send registration notification
                $this->notifier->notify($admin);

                // grant permissions
                $this->authorize($admin,
                                Input::get('Agency_sections'),
                                Input::get('artists_sections'),
                                $is_initial = true);

                return Redirect::route('cms.administration')
                        ->with('success', [Lang::get('success.admin_created')]);
            }

            throw new UnauthorizedException;

        } catch (InvalidAdminException $e) {

            return Redirect::back()
                ->with('errors', $e->messages())
                ->withInput();
        }
    }

    public function edit($id)
    {
        if ($this->admin_permissions->has('update'))
        {
            $edit_admin = $this->admins->find($id);
            // get the default sections
            $Agency_sections   = $this->getAgencySections();
            $artist_sections = $this->getArtistSections();
            $roles = $this->getRoles();
            // get admin's access over the sections
            $Agency_access = Authority::access($edit_admin, $Agency_sections);
            // $artist_access = Authority::access($edit_admin, $artist_sections);

            $edit_admin_Agency_roles = [];
            // $edit_admin_artist_roles = [];

            foreach ($Agency_access->resources as $resource)
            {
                $edit_admin_Agency_roles[$resource->identifier()] = $resource->role->alias;
            }

            // foreach ($artist_access->resources as $resource)
            // {
            //     $edit_admin_artist_roles[$resource->identifier()] = $resource->role->alias;
            // }

            return View::make('cms.pages.administration.edit',
                                compact('edit_admin',
                                        'Agency_sections',
                                        'roles',
                                        // 'artist_sections',
                                        'edit_admin_Agency_roles')
                            );
        }

        throw new UnauthorizedException;
    }

    public function update($id)
    {
        try {

            if ($this->admin_permissions->has('update'))
            {
                $this->validator->validateForUpdate(Input::get('info'));

                $info = Input::get('info');

                // update admin info
                if ($admin = $this->admins->update($id, $info['name'], $info['email']))
                {
                    // update admin access
                    $this->authorize($admin,
                                    Input::get('Agency_sections'),
                                    Input::get('artists_sections'));

                    return Redirect::back()->with('success', [Lang::get('success.updated')])
                            ->withInput();
                }

                return Redirect::back()->with('errors', [Lang::get('errors.unexpected')]);

            }

            throw new UnauthorizedException;

        } catch (InvalidAdminException $e) {
            return Redirect::back()
                ->with('errors', $e->messages())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        if ($this->admin_permissions->has('delete'))
        {
            $admin = $this->admins->find($id);
            $deleted = $this->admins->remove($id);

            if ($deleted)
            {
                Authority::revoke($admin);

                return Redirect::route('cms.administration')
                    ->with('success', [Lang::get('success.admin_deleted')]);
            }

            return Redirect::back()
                ->with('errors', [Lang::get('errors.unexpected')]);
        }

        throw new UnauthorizedException;
    }

    protected function authorize(AuthorableInterface $admin, $Agency, $artists, $is_initial = false)
    {
        $Agency = Input::get('Agency_sections');
        $artists = [];
        // $artists = Input::get('artist_sections');
        $method = $is_initial ? 'initial' : 'authorize';

        $this->authorizer->$method($admin, array_filter($Agency), array_filter($artists));
    }
    /**
     * Returns the roleable Agency sections.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAgencySections()
    {
        return $this->sections->roleable();
    }

    /**
     * Returns the roleable artists sections.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getArtistSections()
    {
        // return $this->artist_sections->roleable();
    }

    /**
     * Returns all the system roles.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getRoles()
    {
        $roles = ['' => 'N/A'];

        foreach ($this->roles->all() as $role)
        {
            $roles[$role->alias] = $role->title;
        }

        return $roles;
    }

}