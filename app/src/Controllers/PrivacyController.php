<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Attributes\Route;

class PrivacyController extends Controller
{
    public function __construct()
    {
        $this->loggedInAuthorization();
    }
    
    #[Route("GET", "/privacy")]
    public function index()
    {
        $this->displayView();
    }
}
