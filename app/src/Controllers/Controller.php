<?php

namespace App\Controllers;

use App\Models\Enums\UserRole;
use App\Models\Exceptions\NotAuthorizedException;

class Controller
{
    public function displayView(?array $viewData = null, ?string $dir = null)
    {
        if ($viewData !== null){
            extract($viewData);
        }
        
        if (isset($_SESSION["error_message"])){
            $errorMessage = $_SESSION["error_message"];
            unset($_SESSION["error_message"]);
        }

        if (isset($_SESSION["success_message"])){
            $successMessage = $_SESSION["success_message"];
            unset($_SESSION["success_message"]);
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
            $_SESSION["error_message"] = "You need to be logged in to perform this action.";
            header("Location: /login");
            exit;
        }
    }

    public function adminAuthorization()
    {
        if ($_SESSION["user"]->getRole() !== UserRole::Admin){
            $_SESSION["error_message"] = "Your account doesn't have the right role to perform this action.";
            header("Location: /portfolio");
            exit;
        }
    }
}
