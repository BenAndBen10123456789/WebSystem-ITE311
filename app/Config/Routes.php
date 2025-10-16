<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Custom routes
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Auth & Dashboard
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');

$routes->get('/logout', 'Auth::logout');

$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/announcements', 'Announcement::index');
$routes->post('/course/enroll', 'Course::enroll');

// Teacher routes (protected) - uses existing Auth controller dashboard
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Auth::dashboard');
});

// Admin routes (protected) - uses existing Auth controller dashboard
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Auth::dashboard');
});
