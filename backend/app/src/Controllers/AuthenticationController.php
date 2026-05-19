<?php

namespace App\Controllers;

use App\Controllers\ApiController;
use App\Models\Dtos\AuthorizationTestDto;
use App\Models\Enums\UserRole;
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
