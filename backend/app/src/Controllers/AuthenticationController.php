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
        http_response_code(404); 
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

    public function currentUser()
    {
        try {
            // Note: generally authentication/authorization code should normally be centralized somewhere (e.g. middleware, base controller, etc.). This is handled inline here for simplicity and demonstration purposes.
            // Get token from Authorization header
            if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
                return $this->sendErrorResponse('Authorization header is required', 401);
            }

            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $headerParts = explode(' ', $authHeader);
            if (count($headerParts) !== 2 || strtolower($headerParts[0]) !== 'bearer') {
                return $this->sendErrorResponse('Invalid authorization header format', 401);
            }
            $token = $headerParts[1];

            $user = $this->authService->getUserFromToken($token);

            if (!$user) {
                return $this->sendErrorResponse('Invalid or expired token', 401);
            }

            // Return user DTO
            $userDTO = new UserDTO($user);
            return $this->sendSuccessResponse($userDTO);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
}
