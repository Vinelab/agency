<?php namespace Agency\Contracts;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

interface ApplicationInterface {

    /**
     * return the password token
     *
     * @return string
     */
    public function codes();

}
