<?php

Route::filter('agency.auth', function(){

    $session = App::make('Agency\Login\SessionManagerInterface');

    if ( ! $session->isOpen())
    {
        if (Request::ajax())
        {
            return Response::json(['error' => [
                'message'  => 'no no no you should be logged in',
                'code'     => 100
            ]]);
        }

        return Redirect::route('landing');
    }

});
