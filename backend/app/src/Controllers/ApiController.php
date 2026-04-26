<?php

namespace App\Controllers\ApiControllers;

use App\Models\ApiResponses\ErrorResponse;
use App\Models\Exceptions\NotFoundException;
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
        try {  
            $loggedInUser = $this->usersService->getUserByUserId(1);

            if ($loggedInUser === null)
            {
                throw new NotFoundException("Logged in user does not exist.");
            }

            $this->loggedInUser = $loggedInUser;
        }
        catch(NotFoundException $ex) {
            $this->displayErrorJson(404, $ex->getMessage());
        }
    }

    public function displayErrorJson(int $errorCode, string $message)
    {
        http_response_code($errorCode);
        echo json_encode(new ErrorResponse($message), JSON_PRETTY_PRINT);
        exit;
    }
     
    public function loggedInAuthorization()
    {
        // if (!isset($_SESSION["user"])){
        //     $this->displayErrorJson(401, "You need to be logged in to perform this action.");
        //     exit;
        // }
    }

    public function adminAuthorization()
    {
        // if ($_SESSION["user"]->getRole() != UserRole::Admin){
        //     $this->displayErrorJson(401, "Your account doesn't have the right role to perform this action.");
        //     exit;
        // }
    }
}
