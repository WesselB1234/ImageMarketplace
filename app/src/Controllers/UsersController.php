<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;
use App\Models\Attributes\Route;

class UsersController extends Controller
{
    private IUsersService $usersService;

    public function __construct()
    {
        $this->loggedInAuthorization();
        $this->adminAuthorization();

        $this->usersService = new UsersService();
    }

    public function index()
    {
        $users = $this->usersService->getAllUsers();

        $this->displayView(["viewModel" => $users], null);
    }

    public function create()
    {
        $this->displayView(null, null);
    }

    public function processCreate()
    {
        $user = User::constructUnknownUser($_POST["username"], $_POST["password"], $_POST["image_tokens"], $_POST["role"]);
        
        try{ 
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

    public function update(array $vars)
    {        
        try{
            if (filter_var($vars["id"], FILTER_VALIDATE_INT) === false){
                throw new Exception("User ID is not valid.");
            }
            
            $userId = $vars["id"];
            $user = $this->usersService->getUserByUserIdOrThrow($userId);

            $this->displayView(["viewModel" => $user], null);
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /users");
        }        
    }

    public function processUpdate(array $vars)
    {
        $userId = $vars["id"];
        $user = User::constructFullyKnownUser($vars["id"], $_POST["username"], $_POST["password"], $_POST["image_tokens"], $_POST["role"]);

        try{
            $this->usersService->updateUser($user);
            
            if ($user->getUserId() === $_SESSION["user"]->getUserId()){
                $user->setUserId($userId);
                $user->setPassword(null);
                $_SESSION["user"] = $user;
            }
            
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
                "Admin/Users/update.php"
            );
        } 
    }
}
