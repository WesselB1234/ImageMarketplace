<?php

namespace App\Controllers;

use App\Controllers\Controller;

class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->isLoggedInAuthorization();
    }
    
    public function index()
    {
        
    }
}
