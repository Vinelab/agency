<?php namespace Agency\Facades;

use Illuminate\Support\Facades\Facade;

class Editorial extends Facade {

    public static function getFacadeAccessor()
    {
        return 'Agency\Content\Editorial';
    }
}
