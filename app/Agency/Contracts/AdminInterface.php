<?php namespace Agency\Contracts;

interface AdminInterface {

    /**
     * return the admin's name
     *
     * @return string
     */
    public function getName();

    /**
     * return the password token
     *
     * @return string
     */
    public function getCode();
}
