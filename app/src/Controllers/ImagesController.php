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
        
        $this->displayView(["viewModel" => $images], "Images/index.php");
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
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
    }

    public function sell(array $vars)
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

            if ($image->getOwnerId() !== $_SESSION["user"]->getUserId() && $_SESSION["user"]->getRole() !== UserRole::Admin){
                throw new NotAuthorizedException("You are not authorized to sell this image.");
            }

            $this->displayView(["viewModel" => new ImageSellingVM($image, null)], null);
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
    }

    public function processSell(array $vars)
    {
        $image = null;
        $imageId = $vars["id"];       

        try{
            $image = $this->imagesService->getImageByImageId($imageId);

            if ($image === null){
                throw new NotFoundException("Image with ID $imageId does not exist.");
            }

            if ($image->getOwnerId() !== $_SESSION["user"]->getUserId() && $_SESSION["user"]->getRole() !== UserRole::Admin){
                throw new NotAuthorizedException("You are not authorized to sell this image.");
            }

            $this->imagesService->sellImage($image->getImageId(), $_POST["price"]);

            setcookie("success_message", "Image successfully put on sale.", time() + 5, "/");
            header("Location: /images/details/$imageId");
        }
        catch(NotFoundException | NotAuthorizedException $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
        catch(Exception $e){

            $this->displayView("Images/sell.php", [
                "viewModel" => new ImageSellingVM($image, $imageId),
                "errorMessage" => $e->getMessage()
            ]);
        }
    }

    public function takeOffSale(array $vars)
    {
        $imageId = $vars["id"];       

        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new NotFoundException("Image ID is not valid.");
            }

            $image = $this->imagesService->getImageByImageId($imageId);

            if ($image === null){
                throw new NotFoundException("Image with ID $imageId does not exist.");
            }

            if ($image->getOwnerId() !== $_SESSION["user"]->getUserId() && $_SESSION["user"]->getRole() !== UserRole::Admin){
                throw new NotAuthorizedException("You are not authorized to take this image off sale.");
            }

            $this->imagesService->updateImageSellingPrice($image->getImageId(), null);

            setcookie("success_message", "Successfully put image off sale.", time() + 5, "/");
            header("Location: /images/details/$imageId");
        }
        catch(NotFoundException $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /images/details/$imageId");
        }
    }

    public function buyImage(array $vars)
    {
        $imageId = $vars["id"];    

        try{
            if (filter_var($imageId, FILTER_VALIDATE_INT) === false) {
                throw new NotFoundException("Image ID is not valid.");
            }

            $image = $this->imagesService->getImageByImageId($imageId);

            if ($image === null){
                throw new NotFoundException("Image with ID $imageId does not exist.");
            }

            if ($image->getOwnerId() === $_SESSION["user"]->getUserId()){
                throw new NotAuthorizedException("You cannot buy your own image.");
            }

            $this->imagesService->buyImage($image, $_SESSION["user"]);

            setcookie("success_message", "Successfully bought image: ".$image->getName()." (Image ID: ".$image->getImageId().").", time() + 5, "/");
            header("Location: /portfolio");
        }
        catch(NotFoundException $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /images/details/$imageId");
        }
    }

    public function upload()
    {
        $this->displayView(null, null);
    }

    public function processUpload()
    {
        $image = Image::constructUnknownImage($_SESSION["user"]->getUserId(), $_SESSION["user"]->getUserId(), $_POST["name"], $_POST["description"], $_POST["alt_text"]);
        
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

    public function moderateImage(array $vars)
    {
        $this->adminAuthorization();

        try{
            if (filter_var($vars["id"], FILTER_VALIDATE_INT) === false) {
                throw new Exception("Image ID is not valid.");
            }

            $imageId = $vars["id"];
            $isModerate = filter_var($vars["isModerate"], FILTER_VALIDATE_BOOL);

            if ($isModerate === null) {
                throw new Exception("IsModerate is not valid.");
            }

            $this->imagesService->updateImageModerationByImageId($imageId, $isModerate);
            setcookie("success_message", "Image successfully ".($isModerate ? "moderated" : "unmoderated").".", time() + 5, "/");
            header("Location: /images/details/$imageId");
        }
        catch(NotFoundException $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /portfolio");
        }
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
            header("Location: /images/details/$imageId");
        }
    }

    public function deleteImage(array $vars)
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

            if ($image->getOwnerId() !== $_SESSION["user"]->getUserId() && $_SESSION["user"]->getRole() !== UserRole::Admin){
                throw new NotAuthorizedException("You are not authorized to delete this image.");
            }

            $this->imagesService->deleteImageByImageId($imageId);

            setcookie("success_message", "Successfully deleted image.", time() + 5, "/");
        } 
        catch(Exception $e){
            setcookie("error_message", $e->getMessage(), time() + 5, "/");
        } 

        header("Location: /portfolio");
    }
}
