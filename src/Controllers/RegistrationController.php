<?php

namespace App\Controllers;

use App\Traits\Renderable; 
use App\Models\User; 
use App\Controllers\BaseController;

class RegistrationController extends BaseController
{
    use Renderable; 

    public function showRegistrationForm()
    {
        $template = 'registration'; // This should match 'registration.mustache'
        $data = [
            'title' => 'Registration',
        ];
        $this->render($template, $data);
    }

    public function register()
    {
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Gather input data
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validation
            $errors = [];
            if (empty($username)) {
                $errors[] = 'Username is required.';
            }
            if (empty($email)) {
                $errors[] = 'Email address is required.';
            }
            if (empty($firstName)) {
                $errors[] = 'First name is required.';
            }
            if (empty($lastName)) {
                $errors[] = 'Last name is required.';
            }
            if (empty($password) || empty($confirmPassword)) {
                $errors[] = 'Password and confirmation are required.';
            } else {
                if (strlen($password) < 8) {
                    $errors[] = 'Password must be at least 8 characters.';
                }
                if (!preg_match('/[0-9]/', $password)) {
                    $errors[] = 'Password must contain at least one numeric character.';
                }
                if (!preg_match('/[a-zA-Z]/', $password)) {
                    $errors[] = 'Password must contain at least one non-numeric character.';
                }
                if (!preg_match('/[!@#$%^&*()\-+]/', $password)) {
                    $errors[] = 'Password must contain at least one special character like (!@#$%^&*-+).';
                }
                if ($password !== $confirmPassword) {
                    $errors[] = 'Passwords do not match.';
                }
            }

            // If there are errors, display them
            if (!empty($errors)) {
                $template = 'registration'; // Re-render the form with errors
                $data = [
                    'title' => 'Registration',
                    'errors' => $errors,
                ];
                $this->render($template, $data);
                return;
            }

            // Create a new user instance and fill it with data
            $user = new User();
            $user->fill([
                'username' => $username,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => $password, // Save the plain password for hashing in the model
            ]);

            // Attempt to register the user
            if ($user->register()) {
                // Registration successful
                $template = 'success'; // Create a success template to show success message
                $data = [
                    'message' => 'Successful Registration',
                    'link' => '/login', // Link to the login form
                ];
                $this->render($template, $data);
            } else {
                // Registration failed
                $template = 'registration'; // Re-render the form with a failure message
                $data = [
                    'title' => 'Registration',
                    'errors' => ['Registration failed. Please try again.'],
                ];
                $this->render($template, $data);
            }
        }
    }
}
