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

$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);
$routes->get('/announcements', 'Announcement::index');
$routes->post('/course/enroll', 'Course::enroll');

// Courses listing & search
$routes->get('/courses', 'Course::index');
$routes->get('/courses/search', 'Course::search');
$routes->post('/courses/search', 'Course::search');
$routes->get('/courses/create', 'Course::create');
$routes->post('/courses/store', 'Course::store');

// Notifications routes
$routes->get('/notifications', 'Notifications::get');
$routes->post('/notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');

// Materials routes
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');

// Teacher routes (protected)
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Auth::dashboard');
    $routes->get('manage-students', 'Teacher::manageStudents');
    $routes->post('approve-enrollment', 'Teacher::approveEnrollment');
    $routes->post('reject-enrollment', 'Teacher::rejectEnrollment');
});

// Admin routes (protected)
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Auth::dashboard');
    $routes->get('manage-users', 'ManageUsers::index');
    $routes->get('manage-users/add', 'ManageUsers::add');
    $routes->post('manage-users/add', 'ManageUsers::add');
    $routes->get('manage-users/edit/(:num)', 'ManageUsers::edit/$1');
    $routes->post('manage-users/edit/(:num)', 'ManageUsers::edit/$1');
    $routes->post('manage-users/change-role/(:num)', 'ManageUsers::changeRole/$1');
    $routes->get('manage-users/deactivate/(:num)', 'ManageUsers::deactivate/$1');
    $routes->get('manage-users/activate/(:num)', 'ManageUsers::activate/$1');
    $routes->get('manage-users/delete/(:num)', 'ManageUsers::delete/$1');
});
