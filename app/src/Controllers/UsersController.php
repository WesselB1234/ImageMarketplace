<?php

namespace App\Controllers;

use App\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->isLoggedInAuthorization();
        $this->isAdminAuthorization();
    }

    public function index()
    {
        
    }

    public function createIndex()
    {
        
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
