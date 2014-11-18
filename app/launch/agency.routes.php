<?php

Route::group(['namespace' => 'Agency\Controllers'], function(){

    Route::get('/', ['as' => 'landing', 'uses' => 'LandingController@index']);

    Route::group(['prefix' => '/login/{provider}'], function(){

        Route::get('/', [
                'as'   => 'login.social',
                'uses' => 'SocialLoginController@index'
            ]);

        Route::get('/authenticate', [
                'as'   => 'login.social.authenticate',
                'uses' => 'SocialLoginController@authenticate'
            ]);

        Route::get('/done', [
                'as' => 'login.social.done',
                'uses' => 'SessionController@index'
            ]);

        Route::get('/complete', [
                'as' => 'login.social.complete',
                'uses' => 'SessionController@complete'
            ]);
    });

    // routes behind login bars

    Route::group(['before'=>'najem.auth'], function(){

        Route::get('/me', ['as' => 'profile', 'uses' => 'ProfileController@show']);

        Route::resource('/posts', 'PostController', ['only'=>['store', 'show', 'update', 'destroy']]);

    });

});
