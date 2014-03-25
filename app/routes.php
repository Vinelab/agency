<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|ok
*/


Route::get('/', function()
{
});


Route::group(['prefix'=>'cms'],function(){

    Route::get('/', [
            'as'   => 'cms.login',
            'uses' => 'Agency\Cms\Controllers\LoginController@index'
    ]);

    Route::get('/login','Agency\Cms\Controllers\LoginController@index');

    Route::post('/login', [
            'as'   => 'cms.login.attempt',
            'uses' => 'Agency\Cms\Controllers\LoginController@login'
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
});


Route::group([ 'before' => 'cms.auth', 'prefix' => 'cms'], function(){


    Route::get('/audience', [
            'as' => 'cms.audience',
            'uses' => 'Agency\Cms\Controllers\DashboardController@index'
    ]);

    Route::get('/logout', [
            'as'   => 'cms.logout',
            'uses' => 'Agency\Cms\Controllers\LoginController@logout'
    ]);

    Route::group(['prefix' => '/dashboard'], function(){

        Route::get('/', [
            'as' => 'cms.dashboard',
            'uses' => 'Agency\Cms\Controllers\DashboardController@index'
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
            'edit'    => 'cms.administration.edit',
            'update'  => 'cms.administration.update',
            'destroy' => 'cms.administration.destroy'
        ],
        'except' => ['show']

    ]);

    Route::group(['prefix' => '/content'], function(){

        

        Route::group(['prefix'=>'/posts'],function(){

            Route::resource('/tags', 'Agency\Cms\Controllers\TagController',
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

            // Route::get("/delete/{id}",[
            //     'as' => 'cms.content.posts.destroy',
            //     'uses' => 'Agency\Cms\Controllers\PostController@destroy'
            // ]);

           

            Route::post("/photos",[
                "as" => "cms.content.posts.photos",
                "uses" => "Agency\Cms\Controllers\TempsController@storePhotos"
            ]);

            Route::post("/{id}",[
                'as' => 'cms.content.posts.update',
                'uses' => 'Agency\Cms\Controllers\PostController@update'
            ]);

        });

        Route::resource('/posts', 'Agency\Cms\Controllers\PostController',
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

        Route::get("/",[
            'as'=>'cms.content',
            'uses'=>'Agency\Cms\Controllers\ContentController@index'
        ]);

    });


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

        Route::resource('applications', 'Agency\Api\Controllers\ApplicationsController',
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

Route::group(['prefix' => 'api','before'=>'code'], function(){

    Route::get('/posts',[
            'as' => 'api.posts',
            'uses' => 'Agency\Api\Controllers\PostsController@index'
        ]);

    Route::get('/posts/{slugOrId}',[
            'as' => 'api.posts.show',
            'uses' => 'Agency\Api\Controllers\PostsController@show'
        ]);

    Route::get('/categories',[
            'as' => 'api.categories',
            'uses' => 'Agency\Api\Controllers\CategoriesController@index'
        ]);

    Route::get('/tags',[
            'as' => 'api.tags',
            'uses' => 'Agency\Api\Controllers\TagsController@index'
        ]);

});

    Route::post('/api/code',[
                'as' => 'api.code.create',
                'uses' => 'Agency\Api\Controllers\CodesController@create'
            ]);

     Route::get('/client',function(){
            return View::make('client');
        });
