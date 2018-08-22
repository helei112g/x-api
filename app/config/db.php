<?php
/**
 * @author  : helei
 * @date    : 2018/3/9 下午8:28
 * @version : 1.0.0
 * @desc    :
 **/

if (APP_ENV_PROD) {// 线上
    return [
        'adapter'  => 'Mysql',
        'host'     => '192.168.33.10',
        'username' => 'root',
        'password' => '123123',
        'dbname'   => 'xapi',
        'charset'  => 'utf8',
        'port'     => 3306,
    ];
} elseif (APP_ENV_TEST) {// 测试
    return [
        'adapter'  => 'Mysql',
        'host'     => '192.168.33.10',
        'username' => 'root',
        'password' => '123123',
        'dbname'   => 'xapi',
        'charset'  => 'utf8',
        'port'     => 3306,
    ];
} else {// 开发
    return [
        'adapter'  => 'Mysql',
        'host'     => '192.168.33.10',
        'username' => 'root',
        'password' => '123123',
        'dbname'   => 'xapi',
        'charset'  => 'utf8',
        'port'     => 3306,
    ];
}
