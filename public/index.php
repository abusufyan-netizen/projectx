<?php

session_start();

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'Hub\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use Hub\Core\Router;
use Hub\Controllers\AuthController;
use Hub\Controllers\ResourceController;

$router = new Router();

// Auth Routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Resource Repository
$router->get('/', 'AuthController@showWelcome');
$router->get('/dashboard', 'ResourceController@index');
$router->get('/upload', 'ResourceController@showUpload');
$router->post('/upload', 'ResourceController@upload');
$router->get('/view/{id}', 'ResourceController@showDetail');
$router->post('/comment/{id}', 'ResourceController@addComment');
$router->get('/fork/{id}', 'ResourceController@fork');
$router->get('/delete/{id}', 'ResourceController@delete');
$router->get('/edit/{id}', 'ResourceController@showEdit');
$router->post('/update/{id}', 'ResourceController@update');
$router->get('/trash', 'ResourceController@showTrash');
$router->get('/restore/{id}', 'ResourceController@restore');

// Collaboration / PRs
$router->get('/collaboration', 'CollaborationController@index');
$router->post('/submit-pr/{id}', 'CollaborationController@submitPR');
$router->get('/review/{id}', 'CollaborationController@review');
$router->post('/merge/{id}', 'CollaborationController@merge');

// Admin Routes
$router->get('/admin', 'AdminController@index');
$router->get('/admin/users', 'AdminController@manageUsers');
$router->get('/admin/user/status/{id}', 'AdminController@updateStatus');
$router->get('/admin/user/delete/{id}', 'AdminController@deleteUser');
$router->post('/admin/user/assign/{id}', 'AdminController@assignUser');

$router->get('/admin/departments', 'AdminController@manageDepartments');
$router->post('/admin/departments/create', 'AdminController@storeDepartment');
$router->get('/admin/departments/delete/{id}', 'AdminController@deleteDepartment');

$router->get('/admin/semesters', 'AdminController@manageSemesters');
$router->post('/admin/semesters/create', 'AdminController@storeSemester');
$router->get('/admin/semesters/delete/{id}', 'AdminController@deleteSemester');

$router->dispatch();
