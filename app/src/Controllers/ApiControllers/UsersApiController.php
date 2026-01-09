<?php

namespace App\Controllers\ApiControllers;

use App\Controllers\ApiControllers\ApiController;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Exceptions\ForbiddenException;
use App\Models\ApiResponses\UserDeletionResponse;
use App\Models\ApiResponses\ErrorResponse;
use App\Models\Attributes\Route;

class UsersApiController extends ApiController
{
    private IUsersService $usersService;

    public function __construct()
    {
        parent::__construct();
        $this->usersService = new UsersService();
        
        $this->loggedInAuthorization();
    }

    public function delete(array $vars)
    {
        $this->adminAuthorization();   
        
        $input = file_get_contents("php://input"); 
        $data = json_decode($input, true); 
        $userId = $data["id"];

        try{
            if (intval($userId) === $_SESSION["user"]->getUserId()){
                throw new ForbiddenException("You cannot delete yourself.");
            }
            
            $this->usersService->deleteUserByUserId($userId);

            http_response_code(200); 
            echo json_encode(new UserDeletionResponse($userId), JSON_PRETTY_PRINT);
        }
        catch(ForbiddenException $e){
            $this->displayErrorJson(403, $e->getMessage());
        }  
        catch(NotFoundException $e){
            $this->displayErrorJson(404, $e->getMessage()); 
        }
        catch(Exception $e){ 
            $this->displayErrorJson(400, $e->getMessage());
        }
    }

    #[Route("GET", "/users/api/getloggedinuser", null)]
    public function getLoggedInUser()
    {
        try{    
            $user = $this->usersService->getUserByUserIdOrThrow($_SESSION["user"]->getUserId());

            http_response_code(200); 
            echo json_encode($user, JSON_PRETTY_PRINT);
        }
        catch(NotFoundException $e){
            $this->displayErrorJson(404, $e->getMessage());
        }
        catch(Exception $e){
            $this->displayErrorJson(400, $e->getMessage());
        }  
    }
}
