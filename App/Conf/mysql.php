<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/12
 * Time: 14:35
 */
namespace App\Conf;
return [
    'MYSQL' => [
        'host'          => '127.0.0.1',
        'port'          => '3306',
        'user'          => 'root',
        'timeout'       => '5',
        'charset'       => 'utf8mb4',
        'password'      => 'root',
        'database'      => 'swoole',
        'POOL_MAX_NUM'  => '20',
        'POOL_TIME_OUT' => '0.1',
    ],
];