<?php

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use RestApi\Middleware\RestApiMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;


Router::scope('/', function (RouteBuilder $routes) {

    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display']);
    // $routes->connect(
    // '/ws/server/',
    // ['prefix' => 'Admin', 'controller' => 'Teste'] );

    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('admin', function ($routes) {


    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);

    $routes->connect('/pacientes/login', ['controller' => 'Pacientes', 'action' => 'login', 'isRest' => true]);

    $routes->fallbacks('DashedRoute');
});




Router::prefix('api', ['isRest' => true], function (RouteBuilder $routes) {

    $routes->connect('/pedidos', ['controller' => 'Pedidos', 'action' => 'index', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/pedidos/jobs', ['controller' => 'Pedidos', 'action' => 'dispatchEmails', 'isRest' => true, 'requireAuthorization' => true]);

    $routes->connect('/clientes', ['controller' => 'Clientes', 'action' => 'index', 'isRest' => true, 'requireAuthorization' => true]);

    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('barramento', ['isRest' => true], function (RouteBuilder $routes) {

    $routes->connect('/getToken', ['controller' => 'Auth', 'action' => 'login', 'isRest' => true, 'requireAuthorization' => false]);

    $routes->post('/', ['controller' => 'Orders', 'action' => 'create', 'isRest' => true, 'requireAuthorization' => true]);

    $routes->get('/', ['controller' => 'Orders', 'action' => 'list', 'isRest' => true, 'requireAuthorization' => true]);

    $routes->fallbacks(DashedRoute::class);
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
