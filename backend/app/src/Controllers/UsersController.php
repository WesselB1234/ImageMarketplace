<?php

namespace App\Controllers;

use App\Mappers\DtoMapper;
use App\Models\Exceptions\ForbiddenException;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IImagesService;
use App\Services\Interfaces\IUsersService;
use App\Models\User;
use App\Models\Enums\UserRole;
use App\Models\Attributes\Route;

class UsersController extends ApiController
{
    private IUsersService $usersService;
    private IAuthenticationService $authenticationService;
    private IImagesService $imagesService;
    private DtoMapper $dtoMapper;
    
    public function __construct(IUsersService $usersService, IAuthenticationService $authenticationService, IImagesService $imagesService, DtoMapper $dtoMapper)
    {
        $this->usersService = $usersService;
        $this->authenticationService = $authenticationService;
        $this->imagesService = $imagesService;
        $this->dtoMapper = $dtoMapper;
    }

    #[Route("GET", "/users/me/portfolio")]
    public function portfolio()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $dtosArray = $this->imagesService->getAllImagesFromUserId($loggedInUser->getUserId());

        http_response_code(200); 
        echo json_encode($dtosArray, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/users")]
    public function getAll()
    {
        $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        
        $dtosArray = $this->usersService->getAllUsers();

        http_response_code(200); 
        echo json_encode($dtosArray, JSON_PRETTY_PRINT);
    }

    #[Route("POST", "/users")]
    public function create()
    {    
        $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        $data = $this->getDataFromInput(["username", "imageTokens", "role"]);

        $dto = $this->usersService->createUserDto($data["username"], $data["password"], intval($data["imageTokens"]), UserRole::from($data["role"]));

        http_response_code(201); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/users/{id}")]
    public function getById(array $requestParams)
    {
        $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        $userId = $requestParams["id"];                

        $dto = $this->usersService->getUserByUserId($userId); 

        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);   
    }

    #[Route("PUT", "/users/{id}")]
    public function update(array $requestParams)
    {
        $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        $userId = $requestParams["id"];   
        $data = $this->getDataFromInput(["username", "imageTokens", "role"]);

        $dto = $this->usersService->updateUser($userId, $data["username"], empty($data["password"]) ? null : $data["password"], $data["imageTokens"], UserRole::from($data["role"]));

        http_response_code(200); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    #[Route("DELETE", "/users/me")]
    public function deleteAccount()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $this->usersService->deleteUserByUserId($loggedInUser->getUserId());
        
        http_response_code(200); 
    }

    #[Route("DELETE", "/users/{id}")]
    public function delete(array $params)
    {
        $loggedInUser = $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        $userId = $params["id"];

        if (intval($userId) === $loggedInUser->getUserId()) {
            throw new ForbiddenException("You cannot delete yourself.");
        }
        
        $this->usersService->deleteUserByUserId($userId);
        http_response_code(200); 
    }
}
