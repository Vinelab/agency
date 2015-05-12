<?php namespace Agency\Facades;

use Illuminate\Support\Facades\Facade;

class Request extends Facade {

    public static function getFacadeAccessor()
    {
        return 'Agency\Http\Request';
    }
}
