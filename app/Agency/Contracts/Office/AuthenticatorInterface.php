<?php namespace Agency\Contracts\Cms;

interface AuthenticatorInterface {

    public function login($email, $password, $remember = false);

    public function logout();
}
