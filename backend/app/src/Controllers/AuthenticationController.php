<?php

namespace App\Controllers;

use App\Controllers\ApiController;
use App\Services\Interfaces\IUsersService;
use App\Models\User;
use App\Models\Enums\UserRole;
use App\Models\ViewModels\AuthenticationVM;
use App\Models\Attributes\Route;
use Exception;

class AuthenticationController extends ApiController 
{
    private IUsersService $usersService;

    public function __construct(IUsersService $usersService){

        parent::__construct($usersService);

        $this->usersService = $usersService;
    }

    #[Route("GET", "/login")]
    public function login()
    {
        echo "hi";
    }

    #[Route("POST", "/login")]
    public function processLogin()
    {       
        http_response_code(201); 
        echo json_encode(["message" => "test"], JSON_PRETTY_PRINT);

        // try{ 
        //     $user = $this->usersService->getUserByUsernameAndPassword($_POST["username"], $_POST["password"]);

        //     if ($user == null){
        //         throw new Exception("Password or username is not correct.");
        //     }
            
        //     $_SESSION["logged_in_user_id"] = $user->getUserId();

        //     header("Location: /");
        // }
        // catch(Exception $e){
        //     $this->displayView([
        //             "viewModel" => new AuthenticationVM($_POST["username"], $_POST["password"]),  
        //             "errorMessage" => $e->getMessage()
        //         ],
        //         "Authentication/Login.php"
        //     );
        // }        
    }

    #[Route("POST", "/register")]
    public function processRegister()
    {
        $user = User::constructUnknownUser($_POST["username"], $_POST["password"], 100, UserRole::User);
        
        try{ 
            $this->usersService->createUser($user);

            $_SESSION["success_message"] = "Successfully created a new account.";
            
            $this->processLogin();
        }
        catch(Exception $e){
            $this->displayView([
                    "viewModel" => new AuthenticationVM($_POST["username"], $_POST["password"]), 
                    "errorMessage" => $e->getMessage()
                ],
                "Authentication/Register.php"
            );
        }
    }
    
    #[Route("GET", "/logout")]
    public function logout()
    {
        $this->loggedInAuthorization();
        
        unset($_SESSION["logged_in_user_id"]);
        $_SESSION["success_message"] = "Successfully logged out of your account.";
        
        header("location: /login");
    }
}
