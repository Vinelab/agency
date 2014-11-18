<?php

App::before(function($request)
{
    //
});


App::after(function($request, $response)
{
    //
});

Route::filter('cms.auth', function(){

    if ( ! Auth::check())
    {
        return Redirect::route('cms.login');
    }

    View::share('admin', Auth::user());
});
