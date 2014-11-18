<?php namespace Agency\Login;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Auth;
use Agency\Contracts\UserInterface;

class SessionManager implements SessionManagerInterface {

    public function open(UserInterface $user)
    {
        Auth::login($user);

        return $user;
    }

    public function user()
    {
        return Auth::user();
    }

    public function isOpen()
    {
        return Auth::check();
    }

    public function close()
    {
        return Auth::logout();
    }
}
