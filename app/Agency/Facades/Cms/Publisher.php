<?php namespace Agency\Facades\Cms;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Illuminate\Support\Facades\Facade;

class Publisher extends Facade {

    public static function getFacadeAccessor() { return 'publisher'; }
}
