<?php namespace Agency\Http\Controllers\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Input, Redirect, Lang, URL, Auth;

use Agency\Contracts\Cms\AuthenticatorInterface;

class LoginController extends Controller {

    /**
     * The authentication instance.
     *
     * @var Illuminate\Auth\AuthManager
     */
    protected $auth;

    public function __construct(AuthenticatorInterface $auth)
    {
        parent::__construct();

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
        $remember = Input::has('remember') && Input::get('remember') === 'on' ? true : false;

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
