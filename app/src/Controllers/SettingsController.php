<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Attributes\Route;
use App\Services\Interfaces\IUsersService;
use Exception;

class SettingsController extends Controller
{
    private IUsersService $usersService;

    public function __construct(IUsersService $usersService)
    {
        $this->loggedInAuthorization();

        $this->usersService = $usersService;
    }
    
    #[Route("GET", "/settings")]
    public function index()
    {
        $this->displayView();
    }

    #[Route("GET", "/settings/deleteaccount")]
    public function deleteAccount()
    {
        try{
            $this->usersService->deleteUserByUserId($_SESSION["user"]->getUserId());
            
            unset($_SESSION["user"]);
            $_SESSION["success_message"] = "Successfully deleted your account.";

            header("location: /login");
        }
        catch(Exception $e){
            $this->displayView([
                    "errorMessage" => $e->getMessage()
                ],
                "Settings/index.php"
            );
        } 
    }
}
