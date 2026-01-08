<?php

namespace App\Controllers\ApiControllers;

use App\Models\Exceptions\NotAuthorizedException;

class ApiController
{
    public function loggedInAuthorization()
    {
        if (!isset($_SESSION["user"])){
            throw new NotAuthorizedException("You need to be logged in to perform this action.");
        }
    }

    public function adminAuthorization()
    {
        if ($_SESSION["user"]->getRole() != UserRole::Admin){
            throw new NotAuthorizedException("Your account doesn't have the right role to perform this action.");
        }
    }
}
