<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Input, Redirect, Lang, URL, Auth;

use Agency\Cms\Authentication\Contracts\AuthenticatorInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;

class LoginController extends Controller {

    /**
     * The authentication instance.
     *
     * @var Illuminate\Auth\AuthManager
     */
    protected $auth;

    public function __construct(SectionRepositoryInterface $sections, AuthenticatorInterface $auth)
    {
        parent::__construct($sections);

        $this->auth = $auth;
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
}