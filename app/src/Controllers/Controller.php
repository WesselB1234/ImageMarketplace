<?php

namespace App\Controllers;

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
        var_dump($_SESSION["user"]->role);
    }
}
