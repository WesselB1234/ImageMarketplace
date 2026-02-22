<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Attributes\Route;
use App\Services\Interfaces\IUsersService;

class PrivacyController extends Controller
{
    public function __construct(IUsersService $usersService)
    {
        parent::__construct($usersService);
        $this->loggedInAuthorization();
    }
    
    #[Route("GET", "/privacy")]
    public function index()
    {
        $this->displayView();
    }
}
