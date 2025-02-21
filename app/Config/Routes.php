<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('test-email', 'Home::sendTest');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard\DashboardController::index');
    $routes->get('/profiles', 'Profiles\ProfilesController::index', ['filter' => 'admin']);
    $routes->get('/profile/(:num)', 'Profiles\ProfileController::show/$1', ['filter' => 'profile']);
    $routes->get('/profile/edit/(:num)', 'Profiles\ProfileController::edit/$1', ['filter' => 'profile']);
    $routes->get('/profile/add', 'Profiles\ProfileController::add', ['filter' => 'admin']);
    $routes->get('/profile/delete/(:num)', 'Profiles\ProfileManagerController::delete/$1', ['filter' => 'admin']);
    $routes->post('/profile/update', 'Profiles\ProfileManagerController::update');
    $routes->post('/profile/create', 'Profiles\ProfileManagerController::create');
    $routes->get('/profile/export/(:segment)/(:num)', 'Profiles\ProfileExportController::export/$1/$2');
    $routes->get('file/(:any)/(:any)', 'Files\FileController::getFile/$1/$2');
});

service('auth')->routes($routes);
