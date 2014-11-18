<?php namespace Agency\Contracts\Office\Notifications;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\Office\RegistrableInterface;

interface AdminRegistrationNotifierInterface {

    public function notify(RegistrableInterface $admin);
}
