<?php
Route::group(['before'=>'code'], function(){

   Route::get('/search',[
        'as' => 'api.search',
        'uses' => 'Agency\Api\Controllers\SearchController@index'
    ]);

    Route::get('/posts',[
            'as' => 'api.posts',
            'uses' => 'Agency\Api\Controllers\PostsController@index'
        ]);

    Route::get('/posts/{slugOrId}',[
            'as' => 'api.posts.show',
            'uses' => 'Agency\Api\Controllers\PostsController@show'
        ]);




});

