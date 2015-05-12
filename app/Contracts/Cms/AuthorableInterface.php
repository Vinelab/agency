<?php namespace Agency\Contracts\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface AuthorableInterface {

    /**
     * The record identifier
     * @return int | string
     */
    public function getKey();

}
