<?php

namespace App\Controllers;

use App\Controllers\Controller;

class AuthenticationController extends Controller 
{
    public function loginIndex()
    {
        $this->displayView("Authentication/login.php", []);
    }

    public function processLogin()
    {
        echo "process login";
    }

    public function registerIndex()
    {
        $this->displayView("Authentication/register.php", []);
    }

    public function processRegister()
    {
        echo "process register";
    }
    
    public function logout()
    {
        $this->loggedInAuthorization();
    }
}
