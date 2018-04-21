<?php
/**
 * @author  : helei
 * @date    : 2018/3/11 下午12:18
 * @version : 1.0.0
 * @desc    :
 **/

$config = [];
if ( APP_ENV_PROD) {// 线上
    $config = [
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        'password' => '123123'
    ];
} elseif (APP_ENV_TEST) {// 测试
    $config = [
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        'password' => '123123'
    ];
} else {// 开发
    $config = [
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        'password' => '123123'
    ];
}

return $config;
