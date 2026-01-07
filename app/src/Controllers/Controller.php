<?php

namespace App\Controllers;

use App\Models\Enums\UserRole;
use App\Models\ApiResponses\ErrorResponse;
use App\Models\Exceptions\NotAuthorizedException;

class Controller
{
    public function displayView(?array $viewData, ?string $dir)
    {
        if ($viewData !== null){
            extract($viewData);
        }

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

        if ($dir !== null){
            require __DIR__ . "../../Views/" . $dir;
        }
        else{
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

            $callerControllerDir  = $trace[1]["class"];
            $callerMethod = $trace[1]["function"];

            $explodedControllerDir = explode("\\", $callerControllerDir);
            $callerControllerName = $explodedControllerDir[count($explodedControllerDir) - 1];
            $cleanClass = str_replace("Controller", "", $callerControllerName);

            require __DIR__ . "../../Views/$cleanClass/$callerMethod.php";
        }
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
        if ($_SESSION["user"]->role != UserRole::Admin){
            setcookie("error_message", "Your account doesn't have the right role to perform this action.", time() + 5, "/");
            header("Location: /login");
        }
    }
    

    public function loggedInAuthorizationApiEndPoint()
    {
        if (!isset($_SESSION["user"])){
            throw new NotAuthorizedException("You need to be logged in to perform this action.");
        }
    }

    public function adminAuthorizationApiEndPoint()
    {
        if ($_SESSION["user"]->role != UserRole::Admin){
            throw new NotAuthorizedException("Your account doesn't have the right role to perform this action.");
        }
    }
}
