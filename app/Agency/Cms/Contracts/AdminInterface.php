<?php namespace Agency\Cms\Contracts;

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

    /**
     * return the database table name
     * that holds the admins
     *
     * @return string
     */
    public function dbTable();
}
