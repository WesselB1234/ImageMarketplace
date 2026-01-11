<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;
use App\Services\Interfaces\IUsersService;
use App\Services\UsersService;
use App\Models\Image;
use App\Models\User;
use App\Models\Enums\UserRole;
use App\Models\ViewModels\ImageDetailsVM;
use App\Models\ViewModels\ImageSellingVM;
use Exception;
use App\Models\Exceptions\NotFoundException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Attributes\Route;

class ImagesController extends Controller
{
    private IImagesService $imagesService;
    private IUsersService $usersService;

    public function __construct(IImagesService $imagesService, IUsersService $usersService)
    {
        $this->loggedInAuthorization();

        $this->imagesService = $imagesService;
        $this->usersService = $usersService;
    }

    #[Route("GET", "/images")]
    public function index()
    {
        $images = $this->imagesService->getAllOnSaleImages();
        
        $this->displayView(["viewModel" => $images], "Images/index.php");
    }

    #[Route("GET", "/images/details", ["id"])]
    public function details(array $vars)
    {
        $imageId = $vars["id"];

        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new Exception("Image ID is not valid.");
            }

            $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
            
            if ($image->getIsOnSale() === false && $_SESSION["user"]->getRole() !== UserRole::Admin && $image->getOwnerId() !== $_SESSION["user"]->getUserId()){
                throw new NotAuthorizedException("You cannot view private off sale images.");
            }

            $ownerUser = null;
            $creatorUser = null;

            if ($image->getOwnerId() !== null){
                $ownerUser = $this->usersService->getUserByUserId($image->getOwnerId());
            }

            if ($image->getCreatorId() !== null){
                $creatorUser = $this->usersService->getUserByUserId($image->getCreatorId());
            }
           
            $this->displayView(["viewModel" => new ImageDetailsVM($image, $ownerUser, $creatorUser)], null);
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /portfolio");
        }
    }

    #[Route("GET", "/images/sell", ["id"])]
    public function sell(array $vars)
    {
        $imageId = $vars["id"];
        
        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new Exception("Image ID is not valid.");
            }
            
            $image = $this->imagesService->getImageByImageIdOrThrow($imageId);

            if (!$this->imagesService->isUserAuthorizedToImage($image)){
                throw new NotAuthorizedException("You are not authorized to sell this image.");
            }

            $this->displayView(["viewModel" => new ImageSellingVM($image, null)]);
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /portfolio");
        }
    }

    #[Route("POST", "/images/sell", ["id"])]
    public function processSell(array $vars)
    {
        $image = null;
        $imageId = $vars["id"];       

        try{
            $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
            $this->imagesService->sellImage($image, $_POST["price"]);

            $_SESSION["success_message"] = "Image successfully put on sale.";
            header("Location: /images/details/$imageId");
        }
        catch(NotFoundException | NotAuthorizedException $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /portfolio");
        }
        catch(Exception $e){
            $this->displayView([
                    "viewModel" => new ImageSellingVM($image, $_POST["price"]),
                    "errorMessage" => $e->getMessage()
                ],
                "Images/sell.php"
            );
        }
    }

    #[Route("GET", "/images/takeoffsale", ["id"])]
    public function takeOffSale(array $vars)
    {
        $imageId = $vars["id"];       

        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new NotFoundException("Image ID is not valid.");
            }

            $this->imagesService->takeImageOffSaleByImageId($imageId, null);

            $_SESSION["success_message"] = "Successfully put image off sale.";
            header("Location: /images/details/$imageId");
        }
        catch(NotFoundException $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /portfolio");
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /images/details/$imageId");
        }
    }

    #[Route("GET", "/images/buy", ["id"])]
    public function buyImage(array $vars)
    {
        $imageId = $vars["id"];    

        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new NotFoundException("Image ID is not valid.");
            }

            $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
            $this->imagesService->buyImage($image);

            $_SESSION["success_message"] = "Successfully bought image: ".$image->getName()." (Image ID: ".$image->getImageId().").";
            header("Location: /portfolio");
        }
        catch(NotFoundException $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /portfolio");
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /images/details/$imageId");
        }
    }

    #[Route("GET", "/images/upload")]
    public function upload()
    {
        $this->displayView();
    }

    #[Route("POST", "/images/upload")]
    public function processUpload()
    {
        $image = Image::constructUnknownImage($_SESSION["user"]->getUserId(), $_SESSION["user"]->getUserId(), $_POST["name"], $_POST["description"], $_POST["alt_text"]);
        
        try{
            $imageId = $this->imagesService->createImage($image);
        
            try{
                $this->imagesService->uploadImageFile($imageId);

                $_SESSION["success_message"] = "Image successfully uploaded!";
                header("Location: /portfolio");
            }
            catch(Exception $e){
                $this->imagesService->deleteImageByImageId($imageId);
                throw new Exception($e->getMessage());
            }
        }
        catch(Exception $e){
            $this->displayView([
                    "viewModel" => $image, 
                    "errorMessage" => $e->getMessage()
                ],
                "Images/upload.php",
            );
        }                
    }

    #[Route("GET", "/images/moderate", ["id", "isModerate"])]
    public function moderateImage(array $vars)
    {
        $this->adminAuthorization();
        
        $imageId = $vars["id"];
        

        try{
            if (filter_var($vars["id"], FILTER_VALIDATE_INT) === false) {
                throw new Exception("Image ID is not valid.");
            }
            
            $isModerate = filter_var($vars["isModerate"], FILTER_VALIDATE_BOOL);

            if ($isModerate === null) {
                throw new Exception("IsModerate is not valid.");
            }

            $this->imagesService->updateImageModerationByImageId($imageId, $isModerate);
            
            $_SESSION["success_message"] = "Image successfully ".($isModerate ? "moderated" : "unmoderated").".";
            header("Location: /images/details/$imageId");
        }
        catch(NotFoundException $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /portfolio");
        }
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
            header("Location: /images/details/$imageId");
        }
    }

    #[Route("GET", "/images/delete", ["id"])]
    public function deleteImage(array $vars)
    {
        $imageId = $vars["id"];
        
        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new Exception("Image ID is not valid.");
            }
            
            $this->imagesService->deleteImageByImageId($imageId);
            $_SESSION["success_message"] = "Successfully deleted image.";
        } 
        catch(Exception $e){
            $_SESSION["error_message"] = $e->getMessage();
        } 

        header("Location: /portfolio");
    }
}
