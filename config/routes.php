<?php

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use RestApi\Middleware\RestApiMiddleware;


Router::scope('/', function (RouteBuilder $routes) {

    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display']);
    // $routes->connect(
    // '/ws/server/',
    // ['prefix' => 'Admin', 'controller' => 'Teste'] );

    $routes->fallbacks(DashedRoute::class);

});

Router::prefix('admin', function ($routes) {

    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);

    $routes->fallbacks('DashedRoute');
    
});


/**
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
