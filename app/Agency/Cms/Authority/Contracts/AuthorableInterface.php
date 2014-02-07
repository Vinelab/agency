<?php namespace Agency\Cms\Authority\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface AuthorableInterface {

    /**
     * The record identifier
     * @return int | string
     */
    public function identifier();

}