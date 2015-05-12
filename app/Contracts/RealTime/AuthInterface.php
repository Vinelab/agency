<?php namespace Agency\Contracts\RealTime;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface AuthInterface {

    /**
     * Login user with the given token.
     *
     * @param  string $token
     *
     * @return boolean
     */
    public function loginWithToken($token);
}
