<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;
use Exception;

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
            throw new Exception("Pootis");
            $this->usersService->createUser($user);

            setcookie("success_message", "Successfully created a new user.", time() + 5, "/");
            header("Location: /users");
        }
        catch(Exception $e){

            $this->displayView("Admin/Users/create.php", [
                "viewModel" => $user, 
                "errorMessage" => $e->getMessage()
            ]);
        }
    }

    public function updateIndex()
    {
        
    }

    public function processUpdate()
    {
        
    }

    public function delete()
    {
        
    }
}
