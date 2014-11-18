<?php

use Agency\Office\Auth\Guard;
use Agency\Office\Auth\UserProvider;
use Illuminate\Foundation\Application;

Config::set('auth.driver', 'agency.management');
Config::set('auth.model', 'Agency\Office\Admin');

Auth::extend('agency.management', function(Application $app)
{
    $provider = new UserProvider($app->make('Illuminate\Hashing\HasherInterface'), Config::get('auth.model'));
    return new Guard($provider, $app->make('Illuminate\Session\Store'));
});
