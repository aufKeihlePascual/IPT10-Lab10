<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login'); #if not logged in, redirect to login page
            exit;
        }

        $user = $_SESSION['user'];

        $data = [
            'title' => 'Dashboard',
            'username' => $user['username'], 
        ];

        return $this->render('dashboard', $data);
    }
}
