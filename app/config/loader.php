<?php

use Phalcon\Loader;

// 加载 vendor 目录的自动加载
require_once BASE_PATH . "/vendor/autoload.php";

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'XApi\Models' => APP_PATH . '/common/models/',
    'XApi\Contracts'        => APP_PATH . '/common/contracts/',
    'XApi\Utils'        => APP_PATH . '/common/utils/',
    'XApi\Base'        => APP_PATH . '/common/base/',
    'XApi\Plugin'        => APP_PATH . '/common/plugin/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'XApi\Modules\V1\Module' => APP_PATH . '/modules/v1/Module.php',
    'XApi\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
