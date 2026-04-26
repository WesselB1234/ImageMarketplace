<?php

namespace App\Controllers\ApiControllers;

use App\Models\ApiResponses\ErrorResponse;
use App\Models\Enums\UserRole;
use App\Models\User;
use App\Services\Interfaces\IUsersService;

class ApiController
{
    private ?IUsersService $usersService = null;
    protected ?User $loggedInUser = null;

    public function __construct(?IUsersService $usersService = null)
    {
        header("Access-Control-Allow-Origin: *"); 
        header("Content-Type: application/json");

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
                $this->displayErrorJson(404, "Logged in user does not exist.");
                exit;
            }

            $this->loggedInUser = $loggedInUser;
        }
    }

    public function displayErrorJson(int $errorCode, string $message)
    {
        http_response_code($errorCode);
        echo json_encode(new ErrorResponse($message), JSON_PRETTY_PRINT);
    }
     
    public function loggedInAuthorization()
    {
        if ($this->loggedInUser === null){
            $this->displayErrorJson(401, "You need to be logged in to perform this action.");
            exit;
        }
    }

    public function adminAuthorization()
    {
        if ($this->loggedInUser->getRole() != UserRole::Admin){
            $this->displayErrorJson(401, "Your account doesn't have the right role to perform this action.");
            exit;
        }
    }
}
