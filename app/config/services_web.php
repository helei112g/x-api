<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Events\Manager as EventsManager;
use XApi\Plugin\SecurityPlugin;

/**
 * Registering a router
 */
$di->setShared('router', function () {
    $router = new Router();

    $router->setDefaultModule('v1');

    return $router;
});

/**
 * The URL component is used to generate all kinds of URLs in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Starts the session the first time some component requests the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
* Set the default namespace for dispatcher
*/
$di->setShared('dispatcher', function() {
    // Create an events manager
    $eventsManager = new EventsManager();

    // 权限检查的插件
    $eventsManager->attach(
        'dispatch:beforeExecuteRoute',
        new SecurityPlugin()
    );

    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('XApi\Modules\V1\Controllers');

    // Assign the events manager to the dispatcher
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});
