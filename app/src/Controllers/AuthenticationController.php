<?php

namespace App\Controllers;

use App\Controllers\Controller;

class AuthenticationController extends Controller 
{
    public function loginIndex()
    {
        $this->displayView("Authentication/login.php", null);
    }

    public function processLogin()
    {
        
    }

    public function registerIndex()
    {
        
    }

    public function processRegister()
    {
        
    }
    
    public function logout()
    {
        $this->loggedInAuthorization();
    }
}
