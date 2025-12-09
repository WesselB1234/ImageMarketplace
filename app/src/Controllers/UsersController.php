<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;

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
