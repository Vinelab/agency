<?php namespace Agency\Contracts\Cms\Notifications;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\Cms\RegistrableInterface;

interface AdminRegistrationNotifierInterface {

    public function notify(RegistrableInterface $admin);
}
