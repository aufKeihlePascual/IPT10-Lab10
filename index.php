<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

try {
    $router = new \Bramus\Router\Router();

    #home
    $router->get('/', '\App\Controllers\HomeController@index');

    #Registration
    $router->get('/registration-form', '\App\Controllers\RegistrationController@showRegistrationForm');
    $router->post('/register', '\App\Controllers\RegistrationController@register');

    #Login
    $router->get('/login', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    #Welcome
    $router->get('/welcome', '\App\Controllers\WelcomeController@showWelcome');

    $router->run();
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
