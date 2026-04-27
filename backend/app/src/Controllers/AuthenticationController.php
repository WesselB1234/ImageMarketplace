<?php

namespace App\Controllers;

use App\Controllers\ApiController;
use App\Models\Dtos\LoginDto;
use App\Models\Dtos\RegisterDto;
use App\Models\Enums\UserRole;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IUsersService;
use App\Models\Attributes\Route;
use Exception;

class AuthenticationController extends ApiController 
{
    private IUsersService $usersService;
    private IAuthenticationService $authenticationService;

    public function __construct(IUsersService $usersService, IAuthenticationService $authenticationService){

        parent::__construct($usersService);

        $this->usersService = $usersService;
        $this->authenticationService = $authenticationService;
    }

    #[Route("POST", "/auth/login")]
    public function processLogin()
    {            
        try{
            $data = $this->getDataFromInput();

            if (empty($data["username"]) || empty($data["password"])){
                throw new Exception("All input fields must be filled.");
            }

            $user = $this->authenticationService->getUserByUsernameAndPassword($data["username"], $data["password"]);
            
            if ($user == null){
                throw new Exception("Password or username is not correct.");
            }

            $dto = new LoginDto($this->authenticationService->generateJwtFromUser($user));
            
            http_response_code(201); 
            echo json_encode($dto, JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $this->displayErrorJson(401, $e->getMessage());
        }        
    }

    #[Route("POST", "/auth/register")]
    public function processRegister()
    {
        try{
            $data = $this->getDataFromInput();

            if (empty($data["username"]) || empty($data["password"])){
                throw new Exception("All input fields must be filled.");
            }

            $user = User::constructUnknownUser($data["username"], $data["password"], 100, UserRole::User); 
            $userId = $this->usersService->createUser($user);
            $user->setUserId($userId);
            
            $dto = new RegisterDto($user, $this->authenticationService->generateJwtFromUser($user));

            http_response_code(201); 
            echo json_encode($dto, JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $this->displayErrorJson(400, $e->getMessage());
        }
    }

    #[Route("GET", "/auth/admin-test")]
    public function adminTest()
    {
        $this->displayErrorJson(404, "test admin");
    }

    #[Route("GET", "/auth/user-test")]
    public function userTest()
    {
        $this->displayErrorJson(404, "test user");
    }
    
//     #[Route("GET", "/logout")]
//     public function logout()
//     {
//         $this->loggedInAuthorization();
        
//         unset($_SESSION["logged_in_user_id"]);
//         $_SESSION["success_message"] = "Successfully logged out of your account.";
        
//         header("location: /login");
//     }

//     public function currentUser()
//     {
//         try {
//             // Note: generally authentication/authorization code should normally be centralized somewhere (e.g. middleware, base controller, etc.). This is handled inline here for simplicity and demonstration purposes.
//             // Get token from Authorization header
//             if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
//                 return $this->sendErrorResponse('Authorization header is required', 401);
//             }

//             $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
//             $headerParts = explode(' ', $authHeader);
//             if (count($headerParts) !== 2 || strtolower($headerParts[0]) !== 'bearer') {
//                 return $this->sendErrorResponse('Invalid authorization header format', 401);
//             }
//             $token = $headerParts[1];

//             $user = $this->authService->getUserFromToken($token);

//             if (!$user) {
//                 return $this->sendErrorResponse('Invalid or expired token', 401);
//             }

//             // Return user DTO
//             $userDTO = new UserDTO($user);
//             return $this->sendSuccessResponse($userDTO);
//         } catch (\Exception $e) {
//             return $this->sendErrorResponse('Internal server error', 500);
//         }
//     }
}
