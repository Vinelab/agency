<?php namespace Agency\Office\Auth\Authentication;

use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Config\Repository as Config;
use Agency\Contracts\Office\AuthenticatorInterface;

class AdminAuthenticator implements AuthenticatorInterface {

    /**
     * The configuration instance.
     *
     * @var Illuminate\Config\Repository
     */
    protected $config;

    /**
     * The authentication instance.
     *
     * @var Illuminate\Auth\AuthManager
     */
    protected $auth;

    public function __construct(Config $config, Auth $auth)
    {
        $this->config = $config;
        $this->auth   = $auth;
    }

    public function login($email, $password, $remember = false)
    {
        return $this->auth->attempt(compact('email', 'password'), $remember);
    }

    public function logout()
    {
        return $this->auth->logout();
    }
}
