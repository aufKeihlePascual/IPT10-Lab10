<?php

namespace App\Controllers;

use App\Models\User;

class LoginController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLoginForm()
    {
        $template = 'login';
        $data = [
            'title' => 'Login', 
        ];
        return $this->render($template, $data);
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->login($username, $password);

        if ($user) {
            
            $_SESSION['user'] = $user;
            header('Location: /dashboard'); 
            exit;
        } else {
            
            $data = [
                'title' => 'Login',
                'error' => 'Login failed. Please check your username and password.',
            ];
            $this->render('login', $data);
        }
    }

    public function logout()
    {
        unset($_SESSION['user']); 
        
        header('Location: /login');
        exit;
    }

}
