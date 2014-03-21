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

Route::post('cms/login/password/email', [
        'as'   => 'cms.login.password.email',
        'uses' => 'Agency\Cms\Controllers\LoginController@sendMail'
    ]);

Route::get('/cms/reset/{code}',[
    'as' => 'cms.login.password.reset',
    'uses' => 'Agency\Cms\Controllers\LoginController@resetPassword'
    ]);
Route::post('/cms/reset',[
    'as' => 'cms.login.password.change',
    'uses' => 'Agency\Cms\Controllers\LoginController@changePassword'
    ]);

Route::group([ 'before' => 'cms.auth', 'prefix' => 'cms'], function(){


    Route::get('/audience', [
            'as' => 'cms.audience',
            'uses' => 'Agency\Cms\Controllers\DashboardController@index'
    ]);

    


    Route::get('/dashboard', [
        'as' => 'cms.dashboard',

        'uses' => 'Agency\Cms\Controllers\DashboardController@index'
    ]);

    Route::get('/dashboard/profile',[
        'as' => 'cms.profile',
        'uses' => 'Agency\Cms\Controllers\AdminController@profile'
    ]);

    Route::post('/dashboard/profile',[
        'as' => 'cms.profile.udpate',
        'uses' => 'Agency\Cms\Controllers\AdminController@updateProfile'
    ]);

    Route::post("/tmp",[
        "as" => "cms.post.tmp",
        "uses" => "Agency\Cms\Controllers\TempsController@storePhotos"
    ]);

    Route::post("/tmp/delete",[
        "as" => "cms.delete.tmp",
        "uses" => "Agency\Cms\Controllers\TempsController@deletePhoto"

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
            'edit'    => 'cms.administration.edit',
            'update'  => 'cms.administration.update',
            'destroy' => 'cms.administration.destroy'
        ],
        'except' => ['show']

    ]);

    Route::group(['prefix' => '/content'], function(){

        Route::resource('/post/tag', 'Agency\Cms\Controllers\TagController',
        [
            'names' => [
                'index'   => 'cms.tag',
                'create'  => 'cms.tag.create',
                'store'   => 'cms.tag.store',
                'edit'    => 'cms.tag.edit',
                'update'  => 'cms.tag.update',
                'destroy' => 'cms.tag.destroy'
            ],
            'except' => ['show']
        ]);

        Route::get("/post/tag/all",[
            'as'=>'cms.tags',
            'uses'=>'Agency\Cms\Controllers\TagController@all'
        ]);

         Route::resource('/post', 'Agency\Cms\Controllers\PostController',
                [
                    'names' => [
                        'index'   => 'cms.post',
                        'create'  => 'cms.post.create',
                        'store'   => 'cms.post.store',
                        'show'    => 'cms.post.show',
                        'edit'    => 'cms.post.edit'
                    ],
                    'except' => ['destroy','update']
        ]);

        Route::get("/post/delete/{id}",[
            'as' => 'cms.post.destroy',
            'uses' => 'Agency\Cms\Controllers\PostController@destroy'
        ]);

        Route::post("/post/{id}",[
            'as' => 'cms.post.update',
            'uses' => 'Agency\Cms\Controllers\PostController@update'
        ]);


        Route::get("/",[
            'as'=>'cms.content',
            'uses'=>'Agency\Cms\Controllers\ContentController@index'
        ]);

    });

    Route::get('/dashboard/password',[
        'as' => 'cms.administration.password',
        'uses' => 'Agency\Cms\Controllers\AdminController@changePassword'
    ]);

    Route::post('/dashboard/password',[
        'as' => 'cms.administration.updatePassword',
        'uses' => 'Agency\Cms\Controllers\AdminController@updatePassword'
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

         Route::resource('applications', 'Agency\Api\Controllers\ApplicationsController',
        [
            'names' => [
                'index'   => 'cms.configuration.permissions',
                'store'   => 'cms.configuration.permissions.store',
                'edit'    => 'cms.configuration.permissions.update',
                'destroy' => 'cms.configuration.permissions.destroy'
            ]
        ]);
    });

   

});

Route::group(['prefix' => 'api'], function(){

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

Route::Get('/tags',[
        'as' => 'api.tags',
        'uses' => 'Agency\Api\Controllers\TagsController@index'
    ]);

});
