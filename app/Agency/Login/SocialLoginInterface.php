<?php namespace Agency\Login;

/**
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */

use Vinelab\Auth\Contracts\ProfileInterface;

interface SocialLoginInterface {

    public function getUser($token);

    public function authenticate(ProfileInterface $profile);

    public function handleIncompleteProfile(ProfileInterface $profile);
}
