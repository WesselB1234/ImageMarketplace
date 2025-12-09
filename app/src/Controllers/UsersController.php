<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;

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

        $this->displayView("Admin/Users/index.php", $users);
    }

    public function createIndex()
    {
        $this->displayView("Admin/Users/create.php", null);
    }

    public function processCreate()
    {
        $user = User::constructUnknownUser($_POST["username"], $_POST["email"], $_POST["password"], $_POST["image_tokens"], $_POST["role"]);
        
        try{ 
            $this->usersService->createUser($user);
            

        }
        catch(Exception $e){
            $this->displayView("Admin/Users/create.php", $user);
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
