<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('test-email', 'Home::sendTest');

//$routes->get('auth', 'App\Modules\Auth\Controllers\AuthPageController::index', ['as' => 'auth']);

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Home::index');
});

service('auth')->routes($routes);
