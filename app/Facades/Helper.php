<?php namespace Agency\Facades;

use Illuminate\Support\Facades\Facade;

class Helper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'Agency\Support\Helper';
    }
}
