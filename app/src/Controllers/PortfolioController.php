<?php

namespace App\Controllers;

use App\Controllers\Controller;

class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->loggedInAuthorization();
    }
    
    public function index()
    {
        $this->displayView("Portfolio/index.php", []);
    }
}
