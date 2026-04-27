<?php

namespace App\Controllers;

use App\Models\Dtos\ErrorDto;
use App\Models\Enums\UserRole;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Exceptions\NotFoundException;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IUsersService;
use Exception;

class ApiController
{
    private ?IUsersService $usersService = null;
    private ?IAuthenticationService $authenticationService = null;
    protected ?User $loggedInUser = null;

    public function __construct(?IUsersService $usersService = null, ?IAuthenticationService $authenticationService = null)
    {
        $this->usersService = $usersService;
        $this->authenticationService = $authenticationService;
    }

    private function setLoggedInUser()
    {
        try {  
            if(!isset($_SERVER["HTTP_AUTHORIZATION"])) {
                throw new NotAuthorizedException("Authorization header is required");
            }

            $authHeader = $_SERVER["HTTP_AUTHORIZATION"];
            $headerParts = explode(" ", $authHeader);
            
            if (count($headerParts) !== 2 || strtolower($headerParts[0]) !== "bearer") {
                throw new NotAuthorizedException("Invalid authorization header format");
            }

            $jwt = $headerParts[1];
            $this->loggedInUser = $this->authenticationService->getUserFromJwt($jwt);

            if ($this->loggedInUser === null) {
                throw new NotAuthorizedException("Invalid or expired token");
            }
        }
        catch(NotAuthorizedException $ex) {
            $this->displayErrorJson(401, $ex->getMessage());
        }
        catch(Exception $ex) {
            $this->displayErrorJson(400, $ex->getMessage());
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
        $this->setLoggedInUser();

        if ($this->loggedInUser === null){
            $this->displayErrorJson(403, "You need to be logged in to perform this action.");
            exit;
        }
    }

    public function adminAuthorization()
    {
        if ($this->loggedInUser->getRole() != UserRole::Admin){
            $this->displayErrorJson(403, "Your account doesn't have the right role to perform this action.");
            exit;
        }
    }

    public function getDataFromInput(): array
    {
        $input = file_get_contents("php://input"); 
        return json_decode($input, true); 
    }
}
