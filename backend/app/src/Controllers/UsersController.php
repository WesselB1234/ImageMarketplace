<?php

namespace App\Controllers;

use App\Mappers\DtoMapper;
use App\Models\Dtos\LoginDto;
use App\Models\Dtos\RegisterDto;
use App\Models\Exceptions\NotAuthorizedException;
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
    
    public function __construct(IUsersService $usersService, IAuthenticationService $authenticationService, IImagesService $imagesService)
    {
        $this->usersService = $usersService;
        $this->authenticationService = $authenticationService;
        $this->imagesService = $imagesService;
    }

    #[Route("GET", "/")] 
    #[Route("GET", "/users/portfolio")]
    public function portfolio()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $images = $this->imagesService->getAllImagesFromUserId($loggedInUser->getUserId());

        $imageDtosArray = DtoMapper::mapImagesArrayToDtoList($images);

        http_response_code(201); 
        echo json_encode($imageDtosArray, JSON_PRETTY_PRINT);
    }

    #[Route("POST", "/users/login")]
    public function login()
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
    public function register()
    {
        $data = $this->getDataFromInput(["username", "password"]);

        $user = User::constructUnknownUser($data["username"], $data["password"], 100, UserRole::User); 
        $userId = $this->usersService->createUser($user);
        $user->setUserId($userId);
        
        $userDto = DtoMapper::mapUserToDto($user);
        
        $dto = new RegisterDto($userDto, $this->authenticationService->generateAuthTokenFromUser($user));

        http_response_code(201); 
        echo json_encode($dto, JSON_PRETTY_PRINT);
    }

    // #[Route("GET", "/users")]
    // public function index()
    // {
    //     $users = $this->usersService->getAllUsers();

    //     $this->displayView(["viewModel" => $users]);
    // }

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

    // #[Route("GET", "/users/update", ["id"])]
    // public function update(array $requestParams)
    // {
    //     $userId = $requestParams["id"];        
        
    //     try{
    //         RequestParamValidator::validateRequestParamId($userId);
            
    //         $user = $this->usersService->getUserByUserIdOrThrow($userId);

    //         $this->displayView(["viewModel" => $user]);
    //     }
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /users");
    //     }        
    // }

    // #[Route("POST", "/users/update", ["id"])]
    // public function processUpdate(array $requestParams)
    // {
    //     $user = null;
    //     $userId = $requestParams["id"];   

    //     try{
    //         if (empty($_POST["password"])) {
    //             $user = User::constructKnownUserWithoutPassword($userId, $_POST["username"], intval($_POST["image_tokens"]), UserRole::from($_POST["role"])); 
    //         }
    //         else{
    //             $user = User::constructFullyKnownUser($userId, $_POST["username"], $_POST["password"], $_POST["image_tokens"], UserRole::from($_POST["role"]));
    //         }
            
    //         $this->usersService->updateUser($user);
            
    //         $_SESSION["success_message"] = "Successfully updated user.";
    //         header("Location: /users");
    //     } 
    //     catch(NotFoundException $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /users");
    //     } 
    //     catch(Exception $e){
    //         $this->displayView([
    //                 "viewModel" => $user,
    //                 "errorMessage" => $e->getMessage()
    //             ],
    //             "Users/update.php"
    //         );
    //     } 
    // }
}
