<?php

Route::group(['namespace' => 'Agency\Cms\Controllers'], function(){

    Route::get('/', [
            'as'   => 'cms.login',
            'uses' => 'LoginController@index'
        ]);

    Route::post('/login', [
            'as'   => 'cms.login.attempt',
            'uses' => 'LoginController@login'
        ]);

    Route::get('/logout', [
            'as'   => 'cms.logout',
            'uses' => 'LoginController@logout'
        ]);

    Route::post('/password/email', [
        'as'   => 'cms.password.email',
        'uses' => 'LoginController@sendMail'
    ]);

    Route::get('/password/reset/{code}',[
        'as' => 'cms.password.reset',
        'uses' => 'LoginController@resetPassword'
    ]);

    Route::post('/password/reset',[
        'as' => 'cms.password.change',
        'uses' => 'LoginController@changePassword'
    ]);

    Route::group([ 'before' => 'cms.auth'], function(){


        Route::group(['prefix' => '/dashboard'], function(){

            Route::get('/', [
                'as' => 'cms.dashboard',
                'uses' => 'DashboardController@index'
            ]);

            Route::get('/profile',[
                'as' => 'cms.dashboard.profile',
                'uses' => 'AdminController@profile'
            ]);

            Route::post('/profile',[
                'as' => 'cms.dashboard.profile.udpate',
                'uses' => 'AdminController@updateProfile'
            ]);

            Route::get('/password',[
                'as' => 'cms.dashboard.password',
                'uses' => 'AdminController@changePassword'
            ]);

            Route::post('/dashboard/password',[
                'as' => 'cms.dashboard.password.update',
                'uses' => 'AdminController@updatePassword'
            ]);
        });

        Route::group(['prefix' =>'/content'], function(){

            Route::get('/',[
                'as' => 'cms.content',
                'uses' => 'ContentController@index'
            ]);

            Route::post('/search', [
                'as' => 'cms.content.search',
                'uses' => 'SearchController@index'
            ]);
            
            Route::group(['prefix'=>'/posts'],function(){

                Route::resource('/tags', 'TagController',
                [
                    'names' => [
                    'index'   => 'cms.content.posts.tags',
                    'create'  => 'cms.content.posts.tags.create',
                    'store'   => 'cms.content.posts.tags.store',
                    'edit'    => 'cms.content.posts.tags.edit',
                    'update'  => 'cms.content.posts.tags.update',
                    'destroy' => 'cms.content.posts.tags.destroy'
                ],
                'except' => ['show']
                ]);

                

                Route::post("/photos",[
                    "as" => "cms.content.posts.photos.store",
                    "uses" => "MediaController@store"
                ]);

                Route::post("/photos/delete",[
                    "as" => "cms.content.posts.photos.destroy",
                    "uses" => "MediaController@destroy"
                ]);

                Route::post("/{id}",[
                    'as' => 'cms.content.posts.update',
                    'uses' => 'PostController@update'
                ]);

            });

            Route::resource('/posts', 'PostController',
                [
                    'names' => [
                        'index'   => 'cms.content.posts',
                        'create'  => 'cms.content.posts.create',
                        'store'   => 'cms.content.posts.store',
                        'edit'    => 'cms.content.posts.edit',
                        'show'    => 'cms.content.posts.show',
                        'destroy' => 'cms.content.posts.destroy'
                    ],
                    'except' => ['update']
                ]);

            Route::get('/{id}',[
                'as' => 'cms.content.show',
                'uses' => 'ContentController@show'
            ]);
           
        });

        Route::resource('/administration', 'AdminController',
        [
            'names' => [
                'index'   => 'cms.administration',
                'create'  => 'cms.administration.create',
                'store'   => 'cms.administration.store',
                'show'    => 'cms.administration.show',
                'edit'    => 'cms.administration.edit',
                'update'  => 'cms.administration.update',
                'destroy' => 'cms.administration.destroy'
            ]
        ]);


        Route::group(['prefix'=>'/teams'],function(){

            Route::resource('/', 'TeamController', [
                'names' => [
                    'index'   => 'cms.teams',
                    'create'  => 'cms.teams.create',
                    'store'   => 'cms.teams.store',
                    'show'    => 'cms.teams.show',
                    'edit'    => 'cms.teams.edit',
                    'update'  => 'cms.teams.update',
                    'destroy' => 'cms.teams.destroy'
                ]
            ]);

            Route::get('/{id}',[
                'as' => 'cms.teams.show',
                'uses' => 'TeamController@show'
            ]);

            Route::get('/{id}/edit',[
                'as' => 'cms.teams.edit',
                'uses' => 'TeamController@edit'
            ]);

            Route::put('/{id}',[
                'as' => 'cms.teams.update',
                'uses' => 'TeamController@update'
            ]);

            Route::delete('/{id}',[
                'as' => 'cms.teams.destroy',
                'uses' => 'TeamController@destroy'
            ]);


            Route::any("/photos",[
                "as" => "cms.teams.photos.store",
                "uses" => "TeamController@storePhoto"
            ]);


            Route::post("/photos/delete",[
                "as" => "cms.teams.photos.destroy",
                "uses" => "TeamController@destroyPhoto"
            ]);
        });


        Route::group(['prefix'=>'/users'],function(){

            Route::resource('/', 'UsersController', [
                'names' => [
                    'index'   => 'cms.users',
                    'create'  => 'cms.users.create',
                    'store'   => 'cms.users.store',
                    'show'    => 'cms.users.show',
                    'edit'    => 'cms.users.edit',
                    'update'  => 'cms.users.update',
                    'destroy' => 'cms.users.destroy'
                ]
            ]);


            Route::any("/photos",[
                "as" => "cms.users.photos.store",
                "uses" => "UsersController@storePhoto"
            ]);


            Route::post("/photos/delete",[
                "as" => "cms.users.photos.destroy",
                "uses" => "UsersController@destroyPhoto"
            ]);
        });



        Route::get('/audience', [
                'as' => 'cms.audience',
                'uses' => 'DashboardController@index'
        ]);

        Route::group(['prefix' => 'configuration'], function() {

            Route::get('', [
                'as' => 'cms.configuration',
                'uses' => 'ConfigurationController@index'
            ]);

            Route::resource('sections', 'SectionController',
                [
                    'names' => [
                        'index'   => 'cms.configuration.sections',
                        'create'  => 'cms.configuration.sections.create',
                        'store'   => 'cms.configuration.sections.store',
                        'show'    => 'cms.configuration.sections.show',
                        'edit'    => 'cms.configuration.sections.update',
                        'destroy' => 'cms.configuration.sections.destroy'
                    ]
                ]);

            Route::resource('roles', 'RoleController',
                [
                    'names' => [
                        'index'   => 'cms.configuration.roles',
                        'create'  => 'cms.configuration.roles.create',
                        'store'   => 'cms.configuration.roles.store',
                        'show'    => 'cms.configuration.roles.show',
                        'edit'    => 'cms.configuration.roles.update',
                        'destroy' => 'cms.configuration.roles.destroy'
                    ]
                ]);

            Route::resource('permissions', 'PermissionController',
                [
                    'names' => [
                        'index'   => 'cms.configuration.permissions',
                        'create'  => 'cms.configuration.permissions.create',
                        'store'   => 'cms.configuration.permissions.store',
                        'show'    => 'cms.configuration.permissions.show',
                        'edit'    => 'cms.configuration.permissions.update',
                        'destroy' => 'cms.configuration.permissions.destroy'
                    ]
                ]);

            Route::resource('applications', 'ApplicationController',
                [
                    'names' => [
                        'index'   => 'cms.configuration.applications',
                        'store'   => 'cms.configuration.applications.store',
                        'edit'    => 'cms.configuration.applications.update',
                        'destroy' => 'cms.configuration.applications.destroy'
                ]
            ]);
        });
    });
});

Route::get('/client', function (){
    return View::make('client');
});
