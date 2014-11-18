<?php namespace Agency\Contracts\Office;

interface AuthenticatorInterface {

    public function login($email, $password, $remember = false);

    public function logout();
}
