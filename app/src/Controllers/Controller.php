<?php

namespace App\Controllers;

use App\Models\Enums\UserRole;

class Controller
{
    public function displayView($dir, $viewData)
    {
        extract($viewData);

        if(isset($_COOKIE["error_message"])){

            $errorMessage = $_COOKIE["error_message"];
            
            unset($_COOKIE["error_message"]);
            setcookie("error_message", false, -1, "/");
        }

        if(isset($_COOKIE["success_message"])){

            $successMessage = $_COOKIE["success_message"];

            unset($_COOKIE["success_message"]);
            setcookie("success_message", false, -1, "/");
        }

        require __DIR__ . "../../Views/" . $dir;
    }

    public function loggedInAuthorization()
    {
        if (!isset($_SESSION["user"])){
            setcookie("error_message", "You need to be logged in to perform this action.", time() + 5, "/");
            header("Location: /login");
        }
    }

    public function adminAuthorization()
    {
        if ($_SESSION["user"]->role == UserRole::Admin){
            setcookie("error_message", "Your account doesn't have the right role to perform this action.", time() + 5, "/");
            header("Location: /login");
        }
    }
}
