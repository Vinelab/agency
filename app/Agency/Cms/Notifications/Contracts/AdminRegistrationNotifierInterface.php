<?php namespace Agency\Cms\Notifications\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Contracts\RegistrableInterface;

interface AdminRegistrationNotifierInterface {

    public function notify(RegistrableInterface $admin);
}