<?php

namespace App\Controllers\ApiControllers;

use App\Models\Exceptions\NotAuthorizedException;
use App\Models\ApiResponses\ErrorResponse;

class ApiController
{
    public function __construct(){
        header("Access-Control-Allow-Origin: *"); 
        header("Content-Type: application/json");
    }

    public function displayErrorJson($message){
        echo json_encode(new ErrorResponse($message), JSON_PRETTY_PRINT);
    }
     
    public function loggedInAuthorization()
    {
        if (!isset($_SESSION["user"])){
            http_response_code(401);
            $this->displayErrorJson("You need to be logged in to perform this action.");
            exit;
        }
    }

    public function adminAuthorization()
    {
        if ($_SESSION["user"]->getRole() != UserRole::Admin){
            http_response_code(401);
            $this->displayErrorJson("Your account doesn't have the right role to perform this action.");
            exit;
        }
    }
}
