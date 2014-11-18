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


    Route::group([ 'before' => 'cms.auth'], function(){

        Route::get('/dashboard', [
            'as' => 'cms.dashboard',
            'uses' => 'DashboardController@index'
        ]);

        Route::get('/artists', [
                'as' => 'cms.artists',
                'uses' => 'DashboardController@index'
            ]);

        Route::get('/artists/{alias}', [
                'as' => 'cms.artists.show',
                'uses' => 'DashboardController@index'
            ]);

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

        Route::get('/content', [
                'as' => 'cms.content',
                'uses' => 'DashboardController@index'
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
        });

    });

});
