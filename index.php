<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

try {
    $router = new \Bramus\Router\Router();

    $router->get('/', '\App\Controllers\HomeController@index'); #   Home route
    $router->get('/registration-form', '\App\Controllers\RegistrationController@showRegistrationForm');
    $router->post('/register', '\App\Controllers\RegistrationController@register');

    $router->get('/login', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');

    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    $router->run();
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
