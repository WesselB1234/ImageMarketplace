<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Attributes\Route;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->loggedInAuthorization();
    }
    
    #[Route("GET", "/settings")]
    public function index()
    {
        $this->displayView();
    }
}
