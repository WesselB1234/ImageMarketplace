<?php

namespace App\Controllers;

use App\Models\Enums\UserRole;
use App\Models\Exceptions\NotFoundException;
use App\Models\User;
use App\Services\Interfaces\IUsersService;

class Controller
{
    private ?IUsersService $usersService = null;
    protected ?User $loggedInUser = null;

    public function __construct(?IUsersService $usersService = null)
    {
        $this->usersService = $usersService;

        if ($this->usersService !== null) {
            $this->setLoggedInUser();
        }
    }

    private function setLoggedInUser()
    {
        if (isset($_SESSION["logged_in_user_id"])){
            
            $loggedInUser = $this->usersService->getUserByUserId($_SESSION["logged_in_user_id"]);

            if ($loggedInUser === null)
            {
                unset($_SESSION["logged_in_user_id"]);
                throw new NotFoundException("Logged in user does not exist.");
            }

            $this->loggedInUser = $loggedInUser;
        }
    }

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
        if ($this->loggedInUser === null){
            $_SESSION["error_message"] = "You need to be logged in to perform this action.";
            header("Location: /login");
            exit;
        }
    }

    public function adminAuthorization()
    {
        if ($this->loggedInUser->getRole() !== UserRole::Admin){
            $_SESSION["error_message"] = "Your account doesn't have the right role to perform this action.";
            header("Location: /portfolio");
            exit;
        }
    }
}
