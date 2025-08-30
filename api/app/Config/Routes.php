<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('login', 'AuthController::login');
$routes->post('register', 'AuthController::register');


$routes->group('api', ['filter' => 'jwt'], function($routes) {
    $routes->get('profile', 'UserController::index');
    $routes->post('profile/update', 'UserController::update');
    $routes->resource('kelas', ['controller' => 'KelasController']);
    $routes->post('kelas/update/(:num)', 'KelasController::update/$1');
    $routes->resource('modul', ['controller' => 'ModulController']);
    $routes->post('modul/update/(:num)', 'ModulController::update/$1');
    $routes->resource('jadwal', ['controller' => 'JadwalController']);
    $routes->post('jadwal/update/(:num)', 'JadwalController::update/$1');
    $routes->resource('nilai', ['controller' => 'NilaiController']);
    $routes->post('nilai/update/(:num)', 'NilaiController::update/$1');
});