<?php

namespace App\Controllers;

use App\Controllers\ApiController;
use App\Models\Dtos\AuthorizationTestDto;
use App\Models\Dtos\LoginDto;
use App\Models\Dtos\RegisterDto;
use App\Models\Dtos\UserDto;
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

        parent::__construct($usersService, $authenticationService);

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

            $dto = new LoginDto($this->authenticationService->generateTokenFromUser($user));
            
            http_response_code(201); 
            echo json_encode($dto, JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            header("X-Auth-Error: invalid_credentials");
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
            
            $dto = new RegisterDto($user, $this->authenticationService->generateTokenFromUser($user));

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
        $this->loggedInAuthorization();
        $this->adminAuthorization();

        $dto = new AuthorizationTestDto($this->loggedInUser);
        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/auth/user-test")]
    public function userTest()
    {
        $this->loggedInAuthorization();

        $dto = new AuthorizationTestDto($this->loggedInUser);
        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/auth/get-logged-in-user")]
    public function getLoggedInUser()
    {
        $this->loggedInAuthorization();

        http_response_code(200); 
        echo json_encode(new UserDto($this->loggedInUser->getUserId(), $this->loggedInUser->getUsername(), $this->loggedInUser->getImageTokens(), $this->loggedInUser->getRole()), JSON_PRETTY_PRINT);
    }
}
