<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
});


Route::get('/cms', [
        'as'   => 'cms.login',
        'uses' => 'Najem\Cms\Controllers\LoginController@index'
    ]);

Route::post('cms/login', [
        'as'   => 'cms.login.attempt',
        'uses' => 'Najem\Cms\Controllers\LoginController@login'
    ]);

Route::get('cms/logout', [
        'as'   => 'cms.logout',
        'uses' => 'Najem\Cms\Controllers\LoginController@logout'
    ]);

Route::group([ 'before' => 'cms.auth', 'prefix' => 'cms'], function(){

    Route::get('/dashboard', [
        'as' => 'cms.dashboard',
        'uses' => 'Najem\Cms\Controllers\DashboardController@index'
    ]);



    Route::resource('/administration', 'Najem\Cms\Controllers\AdminController',
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
            'uses' => 'Najem\Cms\Controllers\DashboardController@index'
        ]);

    Route::get('/audience', [
            'as' => 'cms.audience',
            'uses' => 'Najem\Cms\Controllers\DashboardController@index'
        ]);

    Route::group(['prefix' => 'configuration'], function() {

        Route::get('', [
            'as' => 'cms.configuration',
            'uses' => 'Najem\Cms\Controllers\ConfigurationController@index'
        ]);

        Route::resource('sections', 'Najem\Cms\Controllers\SectionController',
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

        Route::resource('roles', 'Najem\Cms\Controllers\RoleController',
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

        Route::resource('permissions', 'Najem\Cms\Controllers\PermissionController',
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