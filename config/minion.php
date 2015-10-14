<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Router Realm
    |--------------------------------------------------------------------------
    |
    | The realm that the router should use.
    |
    */
    'realm' => 'ablafahita',

    /*
    |--------------------------------------------------------------------------
    | Router Host
    |--------------------------------------------------------------------------
    |
    | The IP or hostname that the router should run under.
    |
    */
    'host' => gethostbyname(env('MINION_CROSSBAR_HOST', 'localhost')),

    /*
    |--------------------------------------------------------------------------
    | Router Port
    |--------------------------------------------------------------------------
    |
    | The port that should be used by the router.
    |
    */
    'port' => env('MINION_CROSSBAR_PORT', 9090),

    /*
    |--------------------------------------------------------------------------
    | Auto-registered Providers
    |--------------------------------------------------------------------------
    |
    | The providers listed here will be automatically registered on the
    | session start of the router, in return their role is to register RPCs,
    | subscribe and publish to topics and pretty much whatever an Internal Client does.
    |
    */
    'providers' => [

        'AblaFahita\RealTime\Providers\CommentsProvider',
    ],

    'debug' => false,

];
