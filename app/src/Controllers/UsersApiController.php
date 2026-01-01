<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;
use App\Models\ApiResponses\UserDeletionResponse;
use App\Models\ApiResponses\ErrorResponse;

class UsersApiController extends Controller
{
    private IUsersService $usersService;

    public function __construct()
    {
        // $this->loggedInAuthorization();
        // $this->adminAuthorization();

        $this->usersService = new UsersService();
    }

    public function delete(array $vars)
    {   
        
        header("Access-Control-Allow-Origin: *"); 
        header("Content-Type: application/json");

        $input = file_get_contents("php://input"); 
        $data = json_decode($input, true); 
        $userId = $data["id"];

        try{
            // if ($userId === $_SESSION["user"]->userId){
            //     throw new Exception("You cannot delete yourself.");
            // }
            
            //$this->usersService->deleteUserByUserId($userId);

            //throw new Exception("bruh momento");

            http_response_code(200); 
            echo json_encode(new UserDeletionResponse($userId));
        } 
        catch(Exception $e){
            http_response_code(404); 
            echo json_encode(new ErrorResponse($e->getMessage()));
        } 
    }
}
