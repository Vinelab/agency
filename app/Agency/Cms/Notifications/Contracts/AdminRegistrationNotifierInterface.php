<?php namespace Agency\Cms\Notifications\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Contracts\RegistrableInterface;

interface AdminRegistrationNotifierInterface {

    /**
     * notify admin by email sending
     * raw password
     *
     * @param {RegistrableInterface} $admin
     */
    public function notify(RegistrableInterface $admin);

    /**
     * send token to reset password
     *
     * @param {RegistrableInterface} $admin
     */
    public function sendCode(RegistrableInterface $admin);
}
