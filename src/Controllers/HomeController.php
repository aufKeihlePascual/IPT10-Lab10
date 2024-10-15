<?php

namespace App\Controllers;

use App\Traits\Renderable; // Ensure this is included
use App\Controllers\BaseController;

class HomeController extends BaseController
{
    use Renderable; // Use the Renderable trait

    public function index()
    {
        $template = 'login'; // This should match 'login.mustache'
        $data = [
            'title' => 'Login',
        ];
        $this->render($template, $data); // Use $this->render() from the trait
    }
}
