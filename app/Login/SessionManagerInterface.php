<?php namespace Agency\Login;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\UserInterface;

interface SessionManagerInterface {

    /**
     * Open a session for a user.
     *
     * @param Agency\UserInterface $user
     * @return mixed
     */
    public function open(UserInterface $user);

    /**
     * Return the currently authenticated
     * user.
     *
     * @return Agency\User
     */
    public function user();

    /**
     * Determine whether the user
     * has a session open already.
     *
     * @return boolean
     */
    public function isOpen();

    /**
     * Close the current session.
     *
     * @return void
     */
    public function close();
}
