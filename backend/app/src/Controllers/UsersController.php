<?php

namespace App\Controllers;

use App\Mappers\DtoMapper;
use App\Models\ApiResponses\UserDeletionDto;
use App\Models\Dtos\LoginDto;
use App\Models\Dtos\RegisterDto;
use App\Models\Exceptions\ForbiddenException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Helpers\RequestParamValidator;
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

    #[Route("GET", "/")] 
    #[Route("GET", "/users/portfolio")]
    public function portfolio()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $images = $this->imagesService->getAllImagesFromUserId($loggedInUser->getUserId());

        $imageDtosArray = DtoMapper::mapImagesArrayToDtoList($images);

        http_response_code(200); 
        echo json_encode($imageDtosArray, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/users")]
    public function index()
    {
        $this->authenticationService->getLoggedInUser();
        $users = $this->usersService->getAllUsers();
        $dtoUsers = $this->dtoMapper->mapUsersArrayToDtoList($users);

        http_response_code(200); 
        echo json_encode($dtoUsers, JSON_PRETTY_PRINT);
    }

    // #[Route("GET", "/users/create")]
    // public function create()
    // {
    //     $this->displayView();
    // }

    // #[Route("POST", "/users/create")]
    // public function processCreate()
    // {
    //     $user = null;
    
    //     try{
    //         $user = User::constructUnknownUser($_POST["username"], $_POST["password"], intval($_POST["image_tokens"]), UserRole::from($_POST["role"])); 
    //         $userId = $this->usersService->createUser($user);

    //         $_SESSION["success_message"] = "Successfully created a new user with User ID $userId.";
    //         header("Location: /users");
    //     }
    //     catch(Exception $e){
    //         $this->displayView([
    //                 "viewModel" => $user, 
    //                 "errorMessage" => $e->getMessage()
    //             ], 
    //             "Users/create.php"
    //         );
    //     }
    // }

    #[Route("GET", "/users/get-by-id", ["id"])]
    public function getById(array $requestParams)
    {
        $userId = $requestParams["id"];        
    
        RequestParamValidator::validateRequestParamId($userId);
        
        $user = $this->usersService->getUserByUserIdOrThrow($userId); 
        $userDto = $this->dtoMapper->mapUserToDto($user);

        http_response_code(200); 
        echo json_encode($userDto, JSON_PRETTY_PRINT);   
    }

    #[Route("PUT", "/users", ["id"])]
    public function update(array $requestParams)
    {
        $userId = $requestParams["id"];   
        RequestParamValidator::validateRequestParamId($userId);

        $data = $this->getDataFromInput(["username", "imageTokens", "role"]);

        if (empty($data["password"])) {
            $user = User::constructKnownUserWithoutPassword($userId, $data["username"], intval($data["imageTokens"]), UserRole::from($data["role"])); 
        }
        else{
            $user = User::constructFullyKnownUser($userId, $data["username"], $data["password"], $data["imageTokens"], UserRole::from($data["role"]));
        }
            
        $this->usersService->updateUser($user);
        $userDto = $this->dtoMapper->mapUserToDto($user);

        http_response_code(200); 
        echo json_encode($userDto, JSON_PRETTY_PRINT);
    }

    #[Route("DELETE", "/users/delete", ["id"])]
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

    #[Route("DELETE", "/users/settings")]
    public function deleteAccount()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $this->usersService->deleteUserByUserId($loggedInUser->getUserId());
        
        http_response_code(200); 
    }
}
