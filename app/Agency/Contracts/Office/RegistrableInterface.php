<?php namespace Agency\Contracts\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface RegistrableInterface {

    /**
     * Returns the password that was generated
     * after registration.
     *
     * @return string
     */
    public function getRegistrationPassword();

    /**
     * Returns the email to which we
     * should be sending the registration
     * email.
     *
     * @return string
     */
    public function getRegistrationEmail();

    /**
     * Returns the name of the registered user.
     *
     * @return string
     */
    public function getName();
}
