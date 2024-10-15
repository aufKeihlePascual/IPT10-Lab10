<?php

namespace App\Controllers;

class WelcomeController extends BaseController
{
    public function showWelcome()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $users = $this->getAllUsers();

        $data = [
            'title' => 'Welcome',
            'message' => 'Welcome to IPT10',
            'users' => $users,
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
        ];

        return $this->render('welcome', $data);
    }

    private function getAllUsers()
    {
        global $conn;
        $stmt = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email FROM users");
        return $stmt->fetchAll();
    }

}
