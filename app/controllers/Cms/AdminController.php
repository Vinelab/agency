<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Request, Auth, Authority, Input, Lang, Redirect, Hash, Session, Validator;

use Agency\Cms\Validators\Contracts\AdminValidatorInterface;

use Agency\Cms\Exceptions\UnauthorizedException;
use Agency\Cms\Exceptions\InvalidAdminException;

use Agency\Cms\Authority\Contracts\AuthorableInterface;
use Agency\Cms\Repositories\Contracts\AdminRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
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
        if (Auth::hasPermission('create'))
        {
            $agency_sections   = $this->getAgencySections();
            $roles = $this->getRoles();

           return View::make(
                'cms.pages.administration.create',
                compact('roles', 'agency_sections')
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

            if (Auth::hasPermission('create'))
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
                                Input::get('agency_sections'),
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
            $agency_sections   = $this->getAgencySections();
            $roles = $this->getRoles();
            // get admin's access over the sections
            $agency_access = Authority::access($edit_admin, $agency_sections);

            $edit_admin_agency_roles = [];

            foreach ($agency_access->resources as $resource)
            {
                $edit_admin_agency_roles[$resource->identifier()] = $resource->role->alias;
            }

            return View::make('cms.pages.administration.edit',
                                compact('edit_admin',
                                        'agency_sections',
                                        'roles',
                                        'edit_admin_agency_roles')
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
                                    Input::get('agency_sections'));

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

    protected function authorize(AuthorableInterface $admin, $agency, $is_initial = false)
    {
        $agency = Input::get('agency_sections');

        $method = $is_initial ? 'initial' : 'authorize';

        $this->authorizer->$method($admin, array_filter($agency));
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

    public function changePassword()
    {
        return View::make('cms.pages.administration.password');
    }

    public function updatePassword()
    {
        $admin = Auth::user();
        $credentials = ['email'=>$admin->email,'password'=>Input::get('old_password')];
        if(Auth::validate($credentials))
        {
            if(Input::get('new_password')==Input::get('retype_new_password'))
            {
                if(Input::get('new_password')!="")
                {
                    $this->admins->changePassword($admin->id,Input::get('new_password'));
                    Session::flash('success',[Lang::get('resetPassword.password_updated_successfully')]);
                    Auth::logout();
                    return Redirect::route('cms.login');
                } else {

                    Session::flash('errors',[Lang::get('resetPassword.new_password_cannot_be_empty')]);
                    return Redirect::back()->withInput();
                }
                
            
            } else {
                Session::flash('errors',[Lang::get('resetPassword.password_does_not_match_the_confirm_password')]);
                return Redirect::back()->withInput();
            }

        } else {
            Session::flash('errors',[Lang::get('resetPassword.current_password_error')]);
            return Redirect::back()->withInput();

        }
    }

    public function profile()
    {
        $admin = Auth::user();
        return View::make('cms.pages.administration.profile',compact('admin'));
    }

    public function updateProfile()
    {
        try {
            $admin = Auth::user();
            $validator = Validator::make( 
            Input::all(),
            ['email' => 'required|email','name'=>'required']
            );
            if($validator->passes())
            {
                $this->admins->updateProfile($admin,Input::all());
                Session::flash('success',[Lang::get('profile.data_update_successfully')]);
                return Redirect::route('cms.dashboard');
            }
        } catch (Exception $e) {
            Session::flash('errors',[Lang::get('profile.something_went_wrong')]);
            return Redirect::route('cms.dashboard');
        }
    }

}