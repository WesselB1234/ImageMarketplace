<?php

namespace App\Controllers;

use App\Controllers\ApiController;
use App\Models\Dtos\AuthorizationTestDto;
use App\Models\Dtos\LoginDto;
use App\Models\Dtos\RegisterDto;
use App\Models\Enums\UserRole;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IUsersService;
use App\Models\Attributes\Route;

class AuthenticationController extends ApiController 
{
    private IUsersService $usersService;
    private IAuthenticationService $authenticationService;

    public function __construct(IUsersService $usersService, IAuthenticationService $authenticationService){
        $this->usersService = $usersService;
        $this->authenticationService = $authenticationService;
    }

    #[Route("POST", "/users/login")]
    public function processLogin()
    {            
        $data = $this->getDataFromInput(["username", "password"]);

        $user = $this->authenticationService->getUserByUsernameAndPassword($data["username"], $data["password"]);
        
        if ($user == null){
            throw new NotAuthorizedException("Password or username is not correct.");
        }

        $dto = new LoginDto($this->authenticationService->generateAuthTokenFromUser($user));
        
        http_response_code(201); 
        echo json_encode($dto, JSON_PRETTY_PRINT);     
    }

    #[Route("POST", "/users/register")]
    public function processRegister()
    {
        $data = $this->getDataFromInput(["username", "password"]);

        $user = User::constructUnknownUser($data["username"], $data["password"], 100, UserRole::User); 
        $userId = $this->usersService->createUser($user);
        $user->setUserId($userId);
        
        $dto = new RegisterDto($user, $this->authenticationService->generateAuthTokenFromUser($user));

        http_response_code(201); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/auth/admin-test")]
    public function adminTest()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);

        $dto = new AuthorizationTestDto($loggedInUser);
        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/auth/user-test")]
    public function userTest()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();

        $dto = new AuthorizationTestDto($loggedInUser);
        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }
}
