<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\UsersService;
use App\Services\Interfaces\IUsersService;
use App\Models\User;
use Exception;

class AuthenticationController extends Controller 
{
    private IUsersService $usersService;

    public function __construct(){
        $this->usersService = new UsersService();
    }

    public function loginIndex()
    {
        $this->displayView("Authentication/login.php", []);
    }

    public function processLogin()
    {       
        try{ 
            $user = $this->usersService->getUserByUsernameAndPassword($_POST["username"], $_POST["password"]);

            if ($user == null){
                throw new Exception("Password or username is not correct.");
            }

            $_SESSION["user"] = $user;

            header("Location: /");
        }
        catch(Exception $e){

            $this->displayView("Authentication/Login.php", [
                //"viewModel" => $user, 
                "errorMessage" => $e->getMessage()
            ]);
        }
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
