<?php
Route::group(['before'=>'code'], function(){

    Route::get('/test',function(){
        return View::make('test');
    });

    Route::get('/teams',[
        'as' => 'api.teams.index',
        'uses' => 'Xfactor\Api\Controllers\TeamController@index'
    ]);

    Route::get('/teams/{id}',[
        'as' => 'api.teams.show',
        'uses' => 'Xfactor\Api\Controllers\TeamController@show'
    ]);

    Route::post('/teams/{id}/join',[
        'as' => 'api.teams.join',
        'uses' => 'Xfactor\Api\Controllers\TeamController@join'
    ]);

    Route::get('/teams/{id}/members',[
        'as' => 'api.teams.members',
        'uses' => 'Xfactor\Api\Controllers\TeamController@members'
    ]);

    Route::get('/teams/{id}/leaderboard',[
        'as' => 'api.teams.leaderboard',
        'uses' => 'Xfactor\Api\Controllers\TeamController@leaderboard'
    ]);


    Route::get('/teams/{id}/score',[
        'as' => 'api.teams.score',
        'uses' => 'Xfactor\Api\Controllers\TeamController@score'
    ]);

    Route::get('/users/{id}',[
        'as' => 'api.users.show',
        'uses' => 'Xfactor\Api\Controllers\UserController@show'
    ]);

    Route::get('/users/{id}/score',[
        'as' => 'api.users.score',
        'uses' => 'Xfactor\Api\Controllers\UserController@score'
    ]);

   Route::post('users/{id}/score',[
        'as' => 'api.users.updatescore',
        'uses' => 'Xfactor\Api\Controllers\UserController@updateScore'
    ]);



});

