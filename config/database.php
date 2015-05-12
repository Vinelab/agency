<?php

return [

	'default' => 'neo4j',

	'connections' => [

		'neo4j' => [
			'driver' => 'neo4j',
			'host' => env('NEO4J_HOST'),
			'port' => env('NEO4J_PORT'),
			'username' => 'neo4j',
			'password' => 'meh'
		],
	],

	'migrations' => 'migrations',

	'redis' => [

		'cluster' => false,

		'default' => [
			'host'     => env('REDIS_CENTRAL_STORE_HOST'),
			'port'     => env('REDIS_CENTRAL_STORE_PORT'),
			'database' => 0,
		],

		'central' => [
			'host' => env('REDIS_CENTRAL_STORE_HOST'),
			'port' => env('REDIS_CENTRAL_STORE_PORT'),
			'database' => 0
		],

	],

];
