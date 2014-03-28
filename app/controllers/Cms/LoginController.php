<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */


use Agency\Cms\Authentication\Contracts\AuthenticatorInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\AdminRepositoryInterface;
use Agency\Cms\Notifications\Contracts\AdminRegistrationNotifierInterface;

use View, Input, Redirect, Lang, URL, Auth, Str, Session;


class LoginController extends Controller {

    /**
     * The authentication instance.
     *
     * @var Illuminate\Auth\AuthManager
     */
    protected $auth;

    public function __construct(SectionRepositoryInterface $sections,
                                 AuthenticatorInterface $auth,
                                 AdminRepositoryInterface $admin,
                                 AdminRegistrationNotifierInterface $notifier)
    {
        parent::__construct($sections);

        $this->auth = $auth;
        $this->admin = $admin;
        $this->notifier = $notifier;
    }

    public function index()
    {
        if (Auth::check())
        {
            return Redirect::route('cms.dashboard');
        }

        return View::make('cms.pages.login.index');
    }

    public function login()
    {
        $remember = Input::has('remember') and Input::get('remember') === 'on' ? true : false;

        if ($this->auth->login(Input::get('email'), Input::get('password'), $remember))
        {
            return Redirect::intended(URL::route('cms.dashboard'));
        }

        $errors = [Lang::get('errors.authentication_failed')];

        return Redirect::back()->with(compact('errors'))->withInput();
    }

    public function logout()
    {
        $this->auth->logout();

        return Redirect::route('cms.login');
    }

    public function sendMail()
    {
        $admin = $this->admin->generateCode(Input::get('email'));
        $this->notifier->sendCode($admin);
        Session::flash('warnings',[Lang::get('resetPassword.check_ur_mail_msg')]);

       return Redirect::route('cms.login');
    }

    public function resetPassword($code)
    {
        $user = $this->admin->findBy('code',$code);
        return View::make('cms.pages.login.resetPassword',compact('user'));
    }

    public function changePassword()
    {
        if(Input::get('password')==Input::get('password-conf'))
        {
            try {
                $admin = $this->admin->findBy('code',Input::get('code'));
                $this->admin->changePassword($admin->id,Input::get('password')); 
                Session::flash('success',[Lang::get('resetPassword.password_updated_successfully')]);
                return View::make('cms.pages.login.index');
            } catch (Exception $e) {
                Session::flash('errors',[$e->getMessage()]);
            }
           

        }else{
            Session::flash('errors',[Lang::get('resetPassword.password_does_not_match_the_confirm_password')]);
        }
    }
}