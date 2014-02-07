<?php namespace Agency\Cms\Authentication\Contracts;

interface AuthenticatorInterface {

    public function login($email, $password, $remember = false);

    public function logout();
}