<?php namespace Agency\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class TokenAuth extends Facade {

    public static function getFacadeAccessor()
    {
        return 'Agency\RealTime\Auth\Auth';
    }
}
