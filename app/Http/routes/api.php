<?php

Route::group(['before'=>'code'], function() {

   Route::get('/search',[
        'as' => 'api.search',
        'uses' => 'Api\SearchController@index'
    ]);

    Route::get('/posts',[
        'as' => 'api.posts',
        'uses' => 'Api\PostsController@index'
    ]);

    Route::get('/posts/{slugOrId}',[
        'as' => 'api.posts.show',
        'uses' => 'Api\PostsController@show'
    ]);


        Route::group([ 'prefix' => 'v1', 'namespace' => 'Api' ], function () {

            /**
             * @link: api.[domain.com]/v1/content?featured={1|0}
             */
            Route::resource('content', 'ContentController', ['only' => ['index']]);

            /**
             * @link: api.[domain.com]/v1/news?category={category}
             * @link: api.[domain.com]/v1/news/{id}?category={category}
             */
            Route::resource('news', 'NewsController', ['only' => ['index', 'show']]);


           
            /**
             * @link: api.[domain.com]/v1/auth/login
             * @link: api.[domain.com]/v1/auth/register
             */
            Route::group(['prefix' => 'auth'], function() {
                Route::post('login/{provider}', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
                Route::post('register', ['as' => 'auth.register', 'uses' => 'AuthController@register']);
                Route::get('verify', ['as' => 'auth.verify', 'uses' => 'AuthController@verify']);
            });

            Route::post('/users/{user_id}/update', ['as' => 'users.update', 'uses' => 'UserController@update']);


            Route::post('/contact/{form}', ['as' => 'contact.submit', 'uses' => 'ContactController@send']);

        });
});
