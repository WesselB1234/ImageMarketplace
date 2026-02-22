<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Models\User;
use App\Models\Enums\UserRole;
use Exception;
use App\Models\Exceptions\NotFoundException;
use App\Models\Attributes\Route;
use App\Models\Helpers\RequestParamValidator;

class UsersController extends Controller
{
    private IUsersService $usersService;

    public function __construct(IUsersService $usersService)
    {
        parent::__construct($usersService);
        $this->loggedInAuthorization();
        $this->adminAuthorization();

        $this->usersService = $usersService;
    }

    #[Route("GET", "/users")]
    public function index()
    {
        $users = $this->usersService->getAllUsers();

        $this->displayView(["viewModel" => $users]);
    }

    #[Route("GET", "/users/create")]
    public function create()
    {
        $this->displayView();
    }

    #[Route("POST", "/users/create")]
    public function processCreate()
    {
        $user = null;
    
        try{
            $user = User::constructUnknownUser($_POST["username"], $_POST["password"], intval($_POST["image_tokens"]), UserRole::from($_POST["role"])); 
            $userId = $this->usersService->createUser($user);

            $_SESSION["success_message"] = "Successfully created a new user with User ID $userId.";
            header("Location: /users");
        }
        catch(Exception $e){
            $this->displayView([
                    "viewModel" => $user, 
                    "errorMessage" => $e->getMessage()
                ], 
                "Users/create.php"
            );
        }
    }

    #[Route("GET", "/users/update", ["id"])]
    public function update(array $requestParams)
    {
        $userId = $requestParams["id"];        
        
        try{
            RequestParamValidator::validateRequestParamId($userId);
            
            $user = $this->usersService->getUserByUserIdOrThrow($userId);

            $this->displayView(["viewModel" => $user]);
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /users");
        }        
    }

    #[Route("POST", "/users/update", ["id"])]
    public function processUpdate(array $requestParams)
    {
        $user = null;
        $userId = $requestParams["id"];   

        try{
            if (empty($_POST["password"])) {
                $user = User::constructKnownUserWithoutPassword($userId, $_POST["username"], intval($_POST["image_tokens"]), UserRole::from($_POST["role"])); 
            }
            else{
                $user = User::constructFullyKnownUser($userId, $_POST["username"], $_POST["password"], $_POST["image_tokens"], UserRole::from($_POST["role"]));
            }
            
            $this->usersService->updateUser($user);
            
            $_SESSION["success_message"] = "Successfully updated user.";
            header("Location: /users");
        } 
        catch(NotFoundException $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /users");
        } 
        catch(Exception $e){
            $this->displayView([
                    "viewModel" => $user,
                    "errorMessage" => $e->getMessage()
                ],
                "Users/update.php"
            );
        } 
    }
}
