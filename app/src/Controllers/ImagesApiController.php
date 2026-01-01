<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;

class ImagesApiController extends Controller
{
    private IImagesService $usersService;

    public function __construct()
    {

        $this->usersService = new UsersService();
    }

    public function index()
    {
        $users = $this->usersService->getAllUsers();

        $this->displayView("Admin/Users/index.php", ["viewModel" => $users]);
    }

     public function getOnSaleImageByImageId(array $vars)
    {
        $images = $this->imagesService->getAllOnSaleImages();
        
        $this->displayView("Images/index.php", ["viewModel" => $images]);
    }

    public function details(array $vars)
    {
        try{
            if (filter_var($vars["id"], FILTER_VALIDATE_INT) === false) {
                throw new Exception("Image ID is not valid.");
            }

            $imageId = $vars["id"];
            $image = $this->imagesService->getImageByImageId($imageId);

            if ($image === null){
                throw new NotFoundException("Image with ID $imageId does not exist.");
            }
            
            if ($image->isOnSale === false){
                throw new NotAuthorizedException("You cannot view private off sale images.");
            }

            $ownerUser = null;
            $creatorUser = null;

            if ($image->ownerId !== null){
                $ownerUser = $this->usersService->getUserByUserId($image->ownerId);
            }

            if ($image->creatorId !== null){
                $creatorUser = $this->usersService->getUserByUserId($image->creatorId);
            }
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
    }
} 