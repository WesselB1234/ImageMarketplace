<?php

namespace App\Controllers;

use App\Controllers\ApiController;
use App\Services\Interfaces\IAuthenticationService;
use App\Models\Attributes\Route;

class AuthController extends ApiController 
{
    private IAuthenticationService $authenticationService;

    public function __construct(IAuthenticationService $authenticationService){
        $this->authenticationService = $authenticationService;
    }

    #[Route("GET", "/auth/me")]
    public function userTest()
    {
        $dto = $this->authenticationService->getLoggedInUserDto();

        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("POST", "/auth/login")]
    public function login()
    {            
        $data = $this->getDataFromInput(["username", "password"]);
        $dto = $this->authenticationService->login($data["username"], $data["password"]);
        
        http_response_code(201); 
        echo json_encode($dto, JSON_PRETTY_PRINT);     
    }

    #[Route("POST", "/auth/register")]
    public function register()
    {
        $data = $this->getDataFromInput(["username", "password"]);
        $dto = $this->authenticationService->register($data["username"], $data["password"]);

        http_response_code(201); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }
}
