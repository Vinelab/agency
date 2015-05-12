<?php

Route::any('/code',[
    'as' => 'api.code.create',
    'uses' => 'Api\CodesController@create'
]);


