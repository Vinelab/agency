<?php namespace Agency\Cms\Authority\Facades;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\Facades\Facade;

class Authority extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return '\Agency\Cms\Authority\Manager'; }
}