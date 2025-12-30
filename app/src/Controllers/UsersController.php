<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;

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

        $this->displayView("Admin/Users/index.php", ["viewModel" => $users]);
    }

    public function createIndex()
    {
        $this->displayView("Admin/Users/create.php", []);
    }

    public function processCreate()
    {
        $user = User::constructUnknownUser($_POST["username"], $_POST["email"], $_POST["password"], $_POST["image_tokens"], $_POST["role"]);
        
        try{ 
            $userId = $this->usersService->createUser($user);

            setcookie("success_message", "Successfully created a new user with User ID $userId.", time() + 5, "/");
            header("Location: /users");
        }
        catch(Exception $e){
            $this->displayView("Admin/Users/create.php", [
                "viewModel" => $user, 
                "errorMessage" => $e->getMessage()
            ]);
        }
    }

    public function updateIndex(array $vars)
    {        
        $userId = $vars["id"];

        try{
            $user = $this->usersService->getUserByUserId($userId);

            if($user === null){
                throw new NotFoundException("User with id ".$userId." does not exist.");
            }

            $this->displayView("Admin/Users/update.php", [
                "viewModel" => $user
            ]);
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /users");
        }        
    }

    public function processUpdate(array $vars)
    {
        $user = User::constructFullyKnownUser($vars["id"], $_POST["username"], $_POST["email"], $_POST["password"], $_POST["image_tokens"], $_POST["role"]);

        try{
            $this->usersService->updateUser($user);

            if ($vars["id"] === $_SESSION["user"]->userId)
            {
                $user->userId = $vars["id"];
                $user->password = null;
                $_SESSION["user"] = $user;
            }
            
            setcookie("success_message", "Successfully updated user.", time() + 5, "/");
            header("Location: /users");
        } 
        catch(NotFoundException $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /users");
        } 
        catch(Exception $e){
            $this->displayView("Admin/Users/update.php", [
                "viewModel" => $user,
                "errorMessage" => $e->getMessage()
            ]);
        } 
    }

    public function delete(array $vars)
    {
        try{
            if ($vars["id"] === $_SESSION["user"]->userId){
                throw new Exception("You cannot delete yourself.");
            }

            $this->usersService->deleteUserByUserId($vars["id"]);
            setcookie("success_message", "Successfully deleted user.", time() + 5, "/");
        } 
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
        } 

        header("Location: /users");
    }
}
