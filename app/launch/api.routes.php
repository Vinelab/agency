<?php

Route::group(['namespace' => 'Agency\Api\Controllers'], function(){

    Route::group(['prefix'=>'/auth/{provider}'], function(){

        Route::get('/', [
                'as'   => 'api.auth.social',
                'uses' => 'SocialLoginController@index'
            ]);

        Route::get('/authenticate', [
                'as'   => 'api.auth.social.authenticate',
                'uses' => 'SocialLoginController@authenticate'
            ]);
    });

});
