<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('test-email', 'Home::sendTest');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/profiles', 'Profiles\ProfilesController::index');
    $routes->get('/profile/(:num)', 'Profiles\ProfileController::show/$1');
    $routes->get('/profile/edit/(:num)', 'Profiles\ProfileController::edit/$1');
    $routes->post('/profile/update', 'Profiles\ProfileController::update');
    $routes->get('file/(:any)/(:any)', 'FileController::getFile/$1/$2');
});

service('auth')->routes($routes);
