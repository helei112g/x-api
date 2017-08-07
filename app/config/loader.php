<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'X-api\Models' => APP_PATH . '/common/models/',
    'X-api'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'X-api\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'X-api\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
