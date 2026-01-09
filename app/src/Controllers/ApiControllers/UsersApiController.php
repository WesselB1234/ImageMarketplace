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

class UsersApiController extends ApiController
{
    private IUsersService $usersService;

    public function __construct()
    {
        parent::__construct();

        $this->usersService = new UsersService();
    }

    public function delete(array $vars)
    {
        $this->loggedInAuthorization();
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
            exit;
        }
        catch(ForbiddenException $e){
            http_response_code(403); 
        }  
        catch(NotAuthorizedException $e){
            http_response_code(401); 
        } 
        catch(NotFoundException $e){
            http_response_code(404); 
        }
        catch(Exception $e){
            http_response_code(400); 
        }  

        $this->displayErrorJson($e->getMessage());
    }

    public function getLoggedInUser()
    {
        $this->loggedInAuthorization();

        try{    
            $user = $this->usersService->getUserByUserIdOrThrow($_SESSION["user"]->getUserId());

            http_response_code(200); 
            echo json_encode($user, JSON_PRETTY_PRINT);
            exit;
        }
        catch(NotAuthorizedException $e){
            http_response_code(401);
            $this->displayErrorJson($e->getMessage()); 
        } 
        catch(NotFoundException $e){
            http_response_code(404); 
            $this->displayErrorJson($e->getMessage());
        }
        catch(Exception $e){
            http_response_code(400); 
            $this->displayErrorJson($e->getMessage());
        }  
    }
}
