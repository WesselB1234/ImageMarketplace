<?php

namespace App\Controllers;

class Controller
{
    public function displayView($dir, $viewModel)
    {
        require __DIR__ . "../../Views/" . $dir;
    }

    public function loggedInAuthorization()
    {

    }

    public function adminAuthorization()
    {

    }
}
