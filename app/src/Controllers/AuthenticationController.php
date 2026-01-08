<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\UsersService;
use App\Services\Interfaces\IUsersService;
use App\Models\User;
use App\Models\Enums\UserRole;
use App\Models\ViewModels\LoginVm;
use App\Models\ViewModels\RegisterVm;
use Exception;

class AuthenticationController extends Controller 
{
    private IUsersService $usersService;

    public function __construct(){
        $this->usersService = new UsersService();
    }

    public function login()
    {
        $this->displayView(null, null);
    }

    public function processLogin()
    {       
        try{ 
            $user = $this->usersService->getUserByUsernameAndPassword($_POST["username"], $_POST["password"]);

            if ($user == null){
                throw new Exception("Password or username is not correct.");
            }
            
            $user->setPassword(null);
            $_SESSION["user"] = $user;

            header("Location: /");
        }
        catch(Exception $e){

            $this->displayView([
                    "viewModel" => new LoginVm($_POST["username"], $_POST["password"]),  
                    "errorMessage" => $e->getMessage()
                ],
                "Authentication/Login.php"
            );
        }
    }

    public function register()
    {
        $this->displayView(null, null);
    }

    public function processRegister()
    {
        $user = User::constructUnknownUser($_POST["username"], $_POST["email"], $_POST["password"], 100, UserRole::User->value);
        
        try{ 
            $this->usersService->createUser($user);

            setcookie("success_message", "Successfully created a new account.", time() + 5, "/");
            $this->processLogin();
        }
        catch(Exception $e){

            $this->displayView([
                    "viewModel" => new RegisterVm($_POST["username"], $_POST["password"], $_POST["email"]), 
                    "errorMessage" => $e->getMessage()
                ],
                "Authentication/Register.php"
            );
        }
    }
    
    public function logout()
    {
        $this->loggedInAuthorization();
        
        unset($_SESSION["user"]);
        
        $this->displayView(null, "Authentication/login.php");
    }
}
