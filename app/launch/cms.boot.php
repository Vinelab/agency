<?php

use Agency\Cms\Auth\Guard;
use Agency\Cms\Auth\UserProvider;
use Illuminate\Foundation\Application;

Config::set('auth.driver', 'agency.management');
Config::set('auth.model', 'Agency\Cms\Admin');

Auth::extend('agency.management', function(Application $app)
{
    $provider = new UserProvider($app->make('Illuminate\Hashing\HasherInterface'), Config::get('auth.model'));
    return new Guard($provider, $app->make('Illuminate\Session\Store'));
});
