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
        'uses' => 'Agency\Cms\Controllers\LoginController@index'
    ]);

Route::get('/cms/login','Agency\Cms\Controllers\LoginController@index');

Route::post('cms/login', [
        'as'   => 'cms.login.attempt',
        'uses' => 'Agency\Cms\Controllers\LoginController@login'
    ]);

Route::get('cms/logout', [
        'as'   => 'cms.logout',
        'uses' => 'Agency\Cms\Controllers\LoginController@logout'
    ]);


Route::group([ 'before' => 'cms.auth', 'prefix' => 'cms'], function(){

    Route::get('/dashboard', [
        'as' => 'cms.dashboard',
        'uses' => 'Agency\Cms\Controllers\DashboardController@index'
    ]);


    Route::get("/content/{slug}",[
        'as'=>"cms.content.show",
        'uses'=>"Agency\Cms\Controllers\ContentController@show"
    ]);




    Route::resource('/administration', 'Agency\Cms\Controllers\AdminController',
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
            'uses' => 'Agency\Cms\Controllers\DashboardController@index'
        ]);

     Route::resource('/post', 'Agency\Cms\Controllers\PostController',
            [
                'names' => [
                    'index'   => 'cms.post',
                    'create'  => 'cms.post.create',
                    'store'   => 'cms.post.store',
                    'show'    => 'cms.post.show',
                    'edit'    => 'cms.post.update',
                ],
                'except' => ['destroy']
    ]);

      Route::get("/post/delete/{id}",[
        'as' => 'cms.post.destroy',
        'uses' => 'Agency\Cms\Controllers\PostController@destroy'
        ]);
     


    // Route::get("/content/{id}","Agency\Cms\Controllers\ContentController@show");
    


    

     Route::resource('/content', 'Agency\Cms\Controllers\ContentController',
            [
                'names' => [
                    'index'   => 'cms.content',
                    'create'  => 'cms.content.create',
                    'store'   => 'cms.content.store',
                    'update'  => 'cms.content.update',
                ],
                'except' => ['show','destroy','edit']
        ]);

     // Route::get("/content/delete/{id}",[
     //    'as' => 'cms.content.destroy',
     //    'uses' => 'Agency\Cms\Controllers\ContentController@destroy'
     //    ]);

    // Route::get("/content/edit/{id}",[
    //     'as' => 'cms.content.edit',
    //     'uses' => 'Agency\Cms\Controllers\ContentController@edit'
    //     ]);


    Route::get('/content/post/assign', [
            'as' => 'cms.content.assign',
            'uses' => 'Agency\Cms\Controllers\ContentController@assign'
        ]);

    // Route::get('/content/{num}', [
    //         'as' => 'cms.content.posts',
    //         'uses' => 'Agency\Cms\Controllers\ContentController@section'
    //     ]);

     // Route::get('/content/{num}', [
     //        'as' => 'cms.content.show',
     //        'uses' => 'Agency\Cms\Controllers\ContentController@section'
     //    ]);

    Route::post('/content/post/assign', [
            'as' => 'cms.content.assignForm',
            'uses' => 'Agency\Cms\Controllers\ContentController@assignForm'
        ]);


    Route::group(['prefix' => 'configuration'], function() {

        Route::get('', [
            'as' => 'cms.configuration',
            'uses' => 'Agency\Cms\Controllers\ConfigurationController@index'
        ]);

        Route::resource('sections', 'Agency\Cms\Controllers\SectionController',
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

        Route::resource('roles', 'Agency\Cms\Controllers\RoleController',
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

        Route::resource('permissions', 'Agency\Cms\Controllers\PermissionController',
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
    
    Route::post("/tmp",[
        "as" => "cms.post.tmp",
        "uses" => "Agency\Cms\Controllers\TempsController@storePhotos"
    ]);

});