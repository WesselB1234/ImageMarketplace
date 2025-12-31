<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\Image;
use App\Models\User;
use App\Models\ViewModels\ImageDetailsVM;
use Exception;
use App\Models\Exceptions\NotFoundException;

class ImagesController extends Controller
{
    private IImagesService $imagesService;
    private IUsersService $usersService;

    public function __construct()
    {
        $this->loggedInAuthorization();

        $this->imagesService = new ImagesService();
        $this->usersService = new UsersService();
    }

    public function index()
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
                throw new Exception("Image with ID $imageId does not exist.");
            }
            
            $ownerUser = null;
            $creatorUser = null;

            if ($image->ownerId !== null){
                $ownerUser = $this->usersService->getUserByUserId($image->ownerId);
            }

            if ($image->creatorId !== null){
                $creatorUser = $this->usersService->getUserByUserId($image->creatorId);
            }
           
            $this->displayView("Images/details.php", ["viewModel" => new ImageDetailsVM($image, $ownerUser, $creatorUser)]);
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
    }

    public function buyIndex()
    {

    }

    public function processBuy()
    {

    }

    public function setOnSale()
    {

    }

    public function removeOnSale()
    {

    }

    public function uploadIndex()
    {
        $this->displayView("Images/upload.php", []);
    }

    public function processUpload()
    {
        $image = Image::constructUnknownImage($_SESSION["user"]->userId, $_SESSION["user"]->userId, $_POST["name"], $_POST["description"], $_POST["alt_text"]);
        
        try{
            $imageId = $this->imagesService->createImage($image);
        
            try{
                $this->imagesService->uploadImageFile($imageId);

                setcookie("success_message", "Image successfully uploaded!", time() + 5, "/");
                header("Location: /portfolio");
            }
            catch(Exception $e){
                $this->imagesService->deleteImageByImageId($imageId);
                throw new Exception($e->getMessage());
            }
        }
        catch(Exception $e){
            $this->displayView("Images/upload.php", [
                "viewModel" => $image, 
                "errorMessage" => $e->getMessage()
            ]);
        }                
    }

    public function moderateImage()
    {
        $this->adminAuthorization();
    }

    public function unModerateImage()
    {
        $this->adminAuthorization();
    }

    public function deleteImage()
    {
        $this->adminAuthorization();
    }
}
