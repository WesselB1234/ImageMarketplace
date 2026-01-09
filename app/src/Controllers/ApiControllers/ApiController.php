<?php

namespace App\Controllers\ApiControllers;

use App\Models\Exceptions\NotAuthorizedException;
use App\Models\ApiResponses\ErrorResponse;
use App\Models\Enums\UserRole;

class ApiController
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *"); 
        header("Content-Type: application/json");
    }

    public function displayErrorJson(int $errorCode, string $message)
    {
        http_response_code($errorCode);
        echo json_encode(new ErrorResponse($message), JSON_PRETTY_PRINT);
    }
     
    public function loggedInAuthorization()
    {
        if (!isset($_SESSION["user"])){
            $this->displayErrorJson(401, "You need to be logged in to perform this action.");
            exit;
        }
    }

    public function adminAuthorization()
    {
        if ($_SESSION["user"]->getRole() != UserRole::Admin){
            $this->displayErrorJson(401, "Your account doesn't have the right role to perform this action.");
            exit;
        }
    }
}
