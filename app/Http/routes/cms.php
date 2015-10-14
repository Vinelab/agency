<?php


Route::group(['middleware' => 'csrf'], function() {

    Route::get('/', [
            'as'   => 'cms.login',
            'uses' => 'Cms\LoginController@index'
        ]);

    Route::post('/login', [
            'as'   => 'cms.login.attempt',
            'uses' => 'Cms\LoginController@login'
        ]);

    Route::get('/logout', [
            'as'   => 'cms.logout',
            'uses' => 'Cms\LoginController@logout'
        ]);

    Route::post('/password/email', [
        'as'   => 'cms.password.email',
        'uses' => 'Cms\LoginController@sendMail'
    ]);

    Route::get('/password/reset/{code}',[
        'as' => 'cms.password.reset',
        'uses' => 'Cms\LoginController@resetPassword'
    ]);

    Route::post('/password/reset',[
        'as' => 'cms.password.change',
        'uses' => 'Cms\LoginController@changePassword'
    ]);


    Route::group([ 'middleware' => 'cms.auth'], function(){

        Route::group(['prefix' => '/dashboard'], function(){

            Route::get('/', [
                'as' => 'cms.dashboard',
                'uses' => 'Cms\DashboardController@index'
            ]);

            Route::get('/profile',[
                'as' => 'cms.dashboard.profile',
                'uses' => 'Cms\AdminController@profile'
            ]);

            Route::post('/profile',[
                'as' => 'cms.dashboard.profile.udpate',
                'uses' => 'Cms\AdminController@updateProfile'
            ]);

            Route::get('/password',[
                'as' => 'cms.dashboard.password',
                'uses' => 'Cms\AdminController@changePassword'
            ]);

            Route::post('/dashboard/password',[
                'as' => 'cms.dashboard.password.update',
                'uses' => 'Cms\AdminController@updatePassword'
            ]);
        });

        Route::group(['prefix' =>'/content'], function(){

            Route::get('/',[
                'as' => 'cms.content',
                'uses' => 'Cms\ContentController@index'
            ]);

            Route::post('/search', [
                'as' => 'cms.content.search',
                'uses' => 'Cms\SearchController@index'
            ]);

            Route::group(['prefix'=>'/posts'],function(){

                Route::resource('/tags', 'Cms\TagController',
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



                Route::post('/photos',[
                    'as' => 'cms.content.posts.photos.store',
                    'uses' => 'Cms\MediaController@store'
                ]);

                Route::post('/photos/delete',[
                    'as' => 'cms.content.posts.photos.destroy',
                    'uses' => 'Cms\MediaController@destroy'
                ]);

                Route::post('/{id}',[
                    'as' => 'cms.content.posts.update',
                    'uses' => 'Cms\PostController@update'
                ]);

            });

            Route::resource('/posts', 'Cms\PostController',
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
                'uses' => 'Cms\ContentController@show'
            ]);


        });

        Route::resource('/administration', 'Cms\AdminController',
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

        Route::group(['prefix' => 'configuration'], function() {

            Route::get('', [
                'as' => 'cms.configuration',
                'uses' => 'Cms\ConfigurationController@index'
            ]);

            Route::resource('sections', 'Cms\SectionController',
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

            Route::resource('roles', 'Cms\RoleController',
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

            Route::resource('permissions', 'Cms\PermissionController',
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

            Route::resource('applications', 'Cms\ApplicationController',
                [
                    'names' => [
                        'index'   => 'cms.configuration.applications',
                        'store'   => 'cms.configuration.applications.store',
                        'edit'    => 'cms.configuration.applications.update',
                        'destroy' => 'cms.configuration.applications.destroy'
                ]
            ]);
        });


        // get all photos "paginated" using AJAX to select from (while selecting from existing photos)
        Route::get('/photos',           ['as' => 'cms.photos',          'uses' => 'Cms\PhotoController@index']);
        Route::post('/photos',          ['as' => 'cms.photos.store',    'uses' => 'Cms\PhotoController@upload']);

        /**
         * required route by the `laravel-editor` package to listen to the embedded `mr-uploader`included with the package
         * similar to `/photos` with a different response
         **/
        Route::post('/upload',          ['as' => 'cms.embedded.photos.store',    'uses' => 'Cms\PhotoController@embedUpload']);

    });

    Route::get('/client', function (){
        return View::make('client');
    });

    /**
     * email template tester
     */
    Route::any('/email-tester', function(){
        return View::make("emails.contact.gawab-contact")
            ->with('name', 'Ibrahim Fleifel')
            ->with('phone', '0627626889')
            ->with('country', 'France')
            ->with('email', 'ibrahim@vinelab.com')
            ->with('message_body', "this is the message body here\nAnd this is another line.");
    });


});
