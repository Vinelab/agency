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

// determine the files to load
$files = ['boot', 'filters', 'routes'];

switch(Request::site())
{
    case 'www':
    default:
        $prefix = 'agency';
        break;

    case 'cms':
        $prefix = 'cms';
        break;

    case 'api':
        $prefix = 'api';
        break;
}

// load launch files
foreach($files as $file)
{
    require_once app_path() . '/launch/' . "$prefix.$file.php";
}

Route::any('/code',[
    'as' => 'api.code.create',
    'uses' => 'Agency\Api\Controllers\CodesController@create'
]);
