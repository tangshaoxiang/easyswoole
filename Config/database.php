<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/11
 * Time: 19:10
 */
return [
    'database' => [
        'driver'    => 'mysql',
        'host'      => env('DB_HOST','127.0.0.1'),
        'database'  => env('DB_DATABASE','swoole'),
        'username'  => env('DB_USERNAME','root'),
        'password'  => env('DB_PASSWORD','root'),
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => ''
    ],
    'redis' => [
        'master' => [
            'host' => env('REDIS_MASTER_HOST','127.0.0.1'),
            'port' => env('REDIS_MASTER_PORT',6379),
            'password' => env('REDIS_MASTER_PASSWORD',''),
        ],
        'slave' => [
            'host' => env('REDIS_SLAVE_HOST','127.0.0.1'),
            'port' => env('REDIS_SLAVE_PORT',6379),
            'password' => env('REDIS_SLAVE_PASSWORD',''),
        ],
    ],
];
