<?php

namespace App\Controllers;

use App\Models\Dtos\ErrorDto;
use App\Models\Exceptions\NotFoundException;
use App\Models\User;
use App\Services\Interfaces\IUsersService;

class ApiController
{
    private ?IUsersService $usersService = null;
    protected ?User $loggedInUser = null;

    public function __construct(?IUsersService $usersService = null)
    {
        // $this->usersService = $usersService;

        // if ($this->usersService !== null) {
        //     $this->setLoggedInUser();
        // }
    }

    private function setLoggedInUser()
    {
        try {  
            $loggedInUser = $this->usersService->getUserByUserId(54);

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
        echo json_encode(new ErrorDto($message), JSON_PRETTY_PRINT);
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

    public function getDataFromInput(): array
    {
        $input = file_get_contents("php://input"); 
        return json_decode($input, true); 
    }
}
