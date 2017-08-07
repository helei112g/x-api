<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'XApi\Models' => APP_PATH . '/common/models/',
    'XApi\Contracts'        => APP_PATH . '/common/contracts/',
    'XApi\Utils'        => APP_PATH . '/common/utils/',
    'XApi\Base'        => APP_PATH . '/common/base/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'XApi\Modules\V1\Module' => APP_PATH . '/modules/v1/Module.php',
    'XApi\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
