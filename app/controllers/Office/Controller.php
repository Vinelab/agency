<?php namespace Agency\Office\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Auth;
use Request;
use Controller as BaseController;
use Agency\Office\Exceptions\UnauthorizedException;

class Controller extends BaseController {

    public function __construct()
    {
        if (Auth::check() && ! Request::isOpen() && ! Auth::hasPermission('read')) throw new UnauthorizedException;
    }

}
