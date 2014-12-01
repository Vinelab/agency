<?php

Route::group(['namespace' => 'Agency\Office\Controllers'], function(){

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
        'uses' => 'Agency\Cms\Controllers\LoginController@sendMail'
    ]);

    Route::get('/password/reset/{code}',[
        'as' => 'cms.password.reset',
        'uses' => 'Agency\Cms\Controllers\LoginController@resetPassword'
    ]);

    Route::post('/password/reset',[
        'as' => 'cms.password.change',
        'uses' => 'Agency\Cms\Controllers\LoginController@changePassword'
    ]);


    Route::group([ 'before' => 'cms.auth'], function(){


        Route::group(['prefix' => '/dashboard'], function(){

            Route::get('/', [
                'as' => 'cms.dashboard',
                'uses' => 'DashboardController@index'
            ]);

            Route::get('/profile',[
                'as' => 'cms.dashboard.profile',
                'uses' => 'Agency\Cms\Controllers\AdminController@profile'
            ]);

            Route::post('/profile',[
                'as' => 'cms.dashboard.profile.udpate',
                'uses' => 'Agency\Cms\Controllers\AdminController@updateProfile'
            ]);

            Route::get('/password',[
                'as' => 'cms.dashboard.password',
                'uses' => 'Agency\Cms\Controllers\AdminController@changePassword'
            ]);

            Route::post('/dashboard/password',[
                'as' => 'cms.dashboard.password.update',
                'uses' => 'Agency\Cms\Controllers\AdminController@updatePassword'
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
