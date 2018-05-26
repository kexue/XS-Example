<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
        ],

        'mysql_goods' => [
            'driver'    => 'mysql',
            'host'      => env('DB_GOODS_HOST', 'localhost'),
            'port'      => env('DB_GOODS_PORT', 3306),
            'database'  => env('DB_GOODS_DATABASE', 'forge'),
            'username'  => env('DB_GOODS_USERNAME', 'forge'),
            'password'  => env('DB_GOODS_PASSWORD', ''),
            'charset'   => env('DB_GOODS_CHARSET', 'utf8'),
            'collation' => env('DB_GOODS_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_GOODS_PREFIX', ''),
            'timezone'  => env('DB_GOODS_TIMEZONE', '+00:00'),
            'strict'    => env('DB_GOODS_STRICT_MODE', false),
        ],

        'mysql_user' => [
            'driver'    => 'mysql',
            'host'      => env('DB_USER_HOST', 'localhost'),
            'port'      => env('DB_USER_PORT', 3306),
            'database'  => env('DB_USER_DATABASE', 'forge'),
            'username'  => env('DB_USER_USERNAME', 'forge'),
            'password'  => env('DB_USER_PASSWORD', ''),
            'charset'   => env('DB_USER_CHARSET', 'utf8'),
            'collation' => env('DB_USER_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_USER_PREFIX', ''),
            'timezone'  => env('DB_USER_TIMEZONE', '+00:00'),
            'strict'    => env('DB_USER_STRICT_MODE', false),
        ],

        'mysql_order' => [
            'driver'    => 'mysql',
            'host'      => env('DB_ORDER_HOST', 'localhost'),
            'port'      => env('DB_ORDER_PORT', 3306),
            'database'  => env('DB_ORDER_DATABASE', 'forge'),
            'username'  => env('DB_ORDER_USERNAME', 'forge'),
            'password'  => env('DB_ORDER_PASSWORD', ''),
            'charset'   => env('DB_ORDER_CHARSET', 'utf8'),
            'collation' => env('DB_ORDER_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_ORDER_PREFIX', ''),
            'timezone'  => env('DB_ORDER_TIMEZONE', '+00:00'),
            'strict'    => env('DB_ORDER_STRICT_MODE', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [
        'cluster' => env('REDIS_CLUSTER', false),
        'client' => 'phpredis',
        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'password' => env('REDIS_PASSWORD', null),
        ],

    ],

];
