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


Route::group([ 'before' => 'cms.auth', 'prefix' => 'cms'], function(){


    Route::get("/sections/initialise",function(){

        $content= Agency\Cms\Section::where("alias","=","content")->first();

        $home = Agency\Cms\Section::create([
            'title'      => 'الرئيسية',
            'alias'      => 'home',
            'icon'       => 'icon',
            'parent_id'  => $content->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);

        $academy = Agency\Cms\Section::create([
            'title'      => 'الاكاديمية',
            'alias'      => 'the_academy',
            'icon'       => 'icon',
            'parent_id'  => $content->id,
            'is_fertile' => true,
            'is_roleable'=> false
        ]);

        $new_academy = Agency\Cms\Section::create([
            'title'      => 'الاكاديمية الجديدية',
            'alias'      => 'new_academy',
            'icon'       => 'icon',
            'parent_id'  => $academy->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);
        
        $students = Agency\Cms\Section::create([
            'title'      => 'الطلاب',
            'alias'      => 'students',
            'icon'       => 'icon',
            'parent_id'  => $academy->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);

        $teachers = Agency\Cms\Section::create([
            'title'      => 'الاساتذة',
            'alias'      => 'teachers',
            'icon'       => 'icon',
            'parent_id'  => $academy->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);

        $host = Agency\Cms\Section::create([
            'title'      => 'مقدمة البرنامج',
            'alias'      => 'host',
            'icon'       => 'icon',
            'parent_id'  => $academy->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);
        
        $the_president = Agency\Cms\Section::create([
            'title'      => 'رئيسة الاكاديمية',
            'alias'      => 'president',
            'icon'       => 'icon',
            'parent_id'  => $academy->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);

        $multimedia = Agency\Cms\Section::create([
            'title'      => 'صور و فيديوهات',
            'alias'      => 'multimedia',
            'icon'       => 'icon',
            'parent_id'  => $content->id,
            'is_fertile' => true,
            'is_roleable'=> false
        ]);

        $twentyfour_24 = Agency\Cms\Section::create([
            'title'      => '24/24',
            'alias'      => 'twentyfour_24',
            'icon'       => 'icon',
            'parent_id'  => $multimedia->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);
        
        $dailies = Agency\Cms\Section::create([
            'title'      => 'اليوميات',
            'alias'      => 'dailies',
            'icon'       => 'icon',
            'parent_id'  => $multimedia->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]);

        $primes = Agency\Cms\Section::create([
            'title'      => 'البرايمات',
            'alias'      => 'primes',
            'icon'       => 'icon',
            'parent_id'  => $multimedia->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]); 

        $latest_news = Agency\Cms\Section::create([
            'title'      => 'اخر الخبار',
            'alias'      => 'latest_news',
            'icon'       => 'icon',
            'parent_id'  => $content->id,
            'is_fertile' => false,
            'is_roleable'=> false
        ]); 

        $exclusive_content = Agency\Cms\Section::create([
            'title'      => 'المحتوى الحصري',
            'alias'      => 'exclusive_content',
            'icon'       => 'icon',
            'parent_id'  => $content->id,
            'is_fertile' => true,
            'is_roleable'=> false
        ]);
        
        $exclusive = Agency\Cms\Section::create([
                    'title'      => 'حصري',
                    'alias'      => 'exclusive',
                    'icon'       => 'icon',
                    'parent_id'  => $exclusive_content->id,
                    'is_fertile' => false,
                    'is_roleable'=> false
        ]);
        
        $behind_the_scenes = Agency\Cms\Section::create([
                            'title'      => 'خلف الكواليس',
                            'alias'      => 'behind_the_scenes',
                            'icon'       => 'icon',
                            'parent_id'  => $exclusive_content->id,
                            'is_fertile' => false,
                            'is_roleable'=> false
                ]);

    });

    


    Route::get('/dashboard', [
        'as' => 'cms.dashboard',

        'uses' => 'Agency\Cms\Controllers\DashboardController@index'
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
                'show'    => 'cms.administration.show',
                'edit'    => 'cms.administration.edit',
                'update'  => 'cms.administration.update',
                'destroy' => 'cms.administration.destroy'
            ]
        ]);

    Route::resource('/content/post/tag', 'Agency\Cms\Controllers\TagController',
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

    Route::get("/content/post/tag/all",[
        'as'=>'cms.tags',
        'uses'=>'Agency\Cms\Controllers\TagController@all'
    ]);

   

    Route::get('/audience', [
            'as' => 'cms.audience',
            'uses' => 'Agency\Cms\Controllers\DashboardController@index'
        ]);

     Route::resource('/content/post', 'Agency\Cms\Controllers\PostController',
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

      Route::get("/content/post/delete/{id}",[
        'as' => 'cms.post.destroy',
        'uses' => 'Agency\Cms\Controllers\PostController@destroy'
        ]);

      Route::post("/content/post/remove/photo",[
        'as' => 'cms.post.remove.photo',
        'uses' => 'Agency\Cms\Controllers\PostController@removePhoto'
        ]);

      Route::post("/content/post/{id}",[
        'as' => 'cms.post.update',
        'uses' => 'Agency\Cms\Controllers\PostController@update'
        ]);


    Route::get("/content",[
        'as'=>'cms.content',
        'uses'=>'Agency\Cms\Controllers\ContentController@index'
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
    
});