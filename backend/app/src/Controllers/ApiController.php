<?php

namespace App\Controllers;

use App\Models\Dtos\ErrorDto;
use App\Models\Enums\UserRole;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IUsersService;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

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
        //try {  
            if(!isset($_SERVER["HTTP_AUTHORIZATION"])) {
                throw new NotAuthorizedException("Authorization header is required.");
            }

            $authHeader = $_SERVER["HTTP_AUTHORIZATION"];
            $headerParts = explode(" ", $authHeader);
            
            if (count($headerParts) !== 2 || strtolower($headerParts[0]) !== "bearer") {
                throw new NotAuthorizedException("Invalid authorization header format.");
            }

            $authToken = $headerParts[1];
            $decodedAuthToken = $this->authenticationService->getDecodedAuthToken($authToken);
            $this->authenticationService->validateAuthToken($decodedAuthToken);
            
            $this->loggedInUser = $this->usersService->getUserByUserId($decodedAuthToken->data->userId);

            if ($this->loggedInUser === null) {
                throw new NotAuthorizedException("User in your auth token does not exist.");
            }

            if ($this->authenticationService->isUserEqualToDecodedAuthToken($this->loggedInUser, $decodedAuthToken) === false) {
                header("Authorization: Bearer ". $this->authenticationService->generateAuthTokenFromUser($this->loggedInUser));
            }
        //}
        // catch(ExpiredException $ex) {
        //     header("X-Auth-Error: invalid_token");
        //     $this->displayErrorJson(401, "Your auth token has expired.");
        // }
        // catch(SignatureInvalidException $ex) {
        //     header("X-Auth-Error: invalid_token");
        //     $this->displayErrorJson(401, "Auth token signature is not valid.");
        // }
        // catch(NotAuthorizedException $ex) {
        //     header("X-Auth-Error: invalid_token");
        //     $this->displayErrorJson(401, $ex->getMessage());
        // }
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
        $contentType = $_SERVER['CONTENT_TYPE'];

        if (str_contains($contentType, 'multipart/form-data')) {
            return array_merge($_POST, $_FILES);
        }

        $input = file_get_contents("php://input"); 
        return json_decode($input, true); 
    }
}
