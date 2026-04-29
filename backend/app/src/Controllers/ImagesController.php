<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IImagesService;
use App\Services\Interfaces\IUsersService;
use App\Models\Image;
use App\Models\Enums\UserRole;
use App\Models\ViewModels\ImageDetailsVM;
use App\Models\ViewModels\ImageSellingVM;
use Exception;
use App\Models\Exceptions\NotFoundException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Attributes\Route;
use App\Models\Helpers\RequestParamValidator;

class ImagesController extends ApiController
{
    private IImagesService $imagesService;
    private IUsersService $usersService;

    public function __construct(IImagesService $imagesService, IUsersService $usersService, IAuthenticationService $authenticationService)
    {
        parent::__construct($usersService, $authenticationService);
        $this->loggedInAuthorization();

        $this->imagesService = $imagesService;
        $this->usersService = $usersService;
    }

    // #[Route("GET", "/images")]
    // public function index()
    // {
    //     $images = $this->imagesService->getAllOnSaleImages();
        
    //     $this->displayView(["viewModel" => $images], "Images/index.php");
    // }

    // #[Route("GET", "/images/details", ["id"])]
    // public function details(array $requestParams)
    // {
    //     $imageId = $requestParams["id"];

    //     try{
    //         RequestParamValidator::validateRequestParamId($imageId);

    //         $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
            
    //         if ($image->getIsOnSale() === false && $this->loggedInUser->getRole() !== UserRole::Admin && $image->getOwnerId() !== $this->loggedInUser->getUserId()){
    //             throw new NotAuthorizedException("You cannot view private off sale images.");
    //         }

    //         $ownerUser = null;
    //         $creatorUser = null;

    //         if ($image->getOwnerId() !== null){
    //             $ownerUser = $this->usersService->getUserByUserId($image->getOwnerId());
    //         }

    //         if ($image->getCreatorId() !== null){
    //             $creatorUser = $this->usersService->getUserByUserId($image->getCreatorId());
    //         }
           
    //         $this->displayView(["viewModel" => new ImageDetailsVM($image, $ownerUser, $creatorUser)], null);
    //     }
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /portfolio");
    //     }
    // }

    // #[Route("GET", "/images/sell", ["id"])]
    // public function sell(array $requestParams)
    // {
    //     $imageId = $requestParams["id"];
        
    //     try{
    //         RequestParamValidator::validateRequestParamId($imageId);
            
    //         $image = $this->imagesService->getImageByImageIdOrThrow($imageId);

    //         if (!$this->imagesService->isUserAuthorizedToImage($image)){
    //             throw new NotAuthorizedException("You are not authorized to sell this image.");
    //         }

    //         $this->displayView(["viewModel" => new ImageSellingVM($image, null)]);
    //     }
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /portfolio");
    //     }
    // }

    // #[Route("POST", "/images/sell", ["id"])]
    // public function processSell(array $requestParams)
    // {
    //     $image = null;
    //     $imageId = $requestParams["id"];       

    //     try{
    //         $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
    //         $this->imagesService->sellImage($image, $_POST["price"]);

    //         $_SESSION["success_message"] = "Image successfully put on sale.";
    //         header("Location: /images/details/$imageId");
    //     }
    //     catch(NotFoundException | NotAuthorizedException $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /portfolio");
    //     }
    //     catch(Exception $e){
    //         $this->displayView([
    //                 "viewModel" => new ImageSellingVM($image, $_POST["price"]),
    //                 "errorMessage" => $e->getMessage()
    //             ],
    //             "Images/sell.php"
    //         );
    //     }
    // }

    // #[Route("GET", "/images/takeoffsale", ["id"])]
    // public function takeOffSale(array $requestParams)
    // {
    //     $imageId = $requestParams["id"];       

    //     try{
    //         RequestParamValidator::validateRequestParamId($imageId);

    //         $this->imagesService->takeImageOffSaleByImageId($imageId);

    //         $_SESSION["success_message"] = "Successfully put image off sale.";
    //         header("Location: /images/details/$imageId");
    //     }
    //     catch(NotFoundException $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /portfolio");
    //     }
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /images/details/$imageId");
    //     }
    // }

    // #[Route("GET", "/images/buy", ["id"])]
    // public function buyImage(array $requestParams)
    // {
    //     $imageId = $requestParams["id"];    

    //     try{
    //         RequestParamValidator::validateRequestParamId($imageId);

    //         $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
    //         $this->imagesService->buyImage($image);

    //         $_SESSION["success_message"] = "Successfully bought image: ".$image->getName()." (Image ID: ".$image->getImageId().").";
    //         header("Location: /portfolio");
    //     }
    //     catch(NotFoundException $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /portfolio");
    //     }
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /images/details/$imageId");
    //     }
    // }

    #[Route("POST", "/images/upload")]
    public function processUpload()
    {
        try{
            $imageId = null;
            
            try{
                $data = $this->getDataFromInput();
                $image = Image::constructUnknownImage($this->loggedInUser->getUserId(), $this->loggedInUser->getUserId(), $data["name"], $data["description"], $data["altText"]);
                
                if (!isset($data["image"])){
                    throw new NotFoundException("No image file has been sent to the server.");
                }
                
                $imageFile = $data["image"];

                $this->imagesService->validateImageFile($imageFile);
                $imageId = $this->imagesService->createImage($image);
                $this->imagesService->uploadImageFile($imageFile, $imageId);

                http_response_code(201);
            }
            catch(Exception $e){

                if ($imageId !== null){
                    $this->imagesService->deleteImageByImageId($imageId);       
                }
                
                throw new Exception($e->getMessage());
            }
        }
        catch(Exception $e){
            $this->displayErrorJson(400, $e->getMessage());
        }                
    }

    // #[Route("GET", "/images/moderate", ["id", "isModerate"])]
    // public function moderateImage(array $requestParams)
    // {
    //     $this->adminAuthorization();
        
    //     $imageId = $requestParams["id"];
    //     $isModerateRaw = $requestParams["isModerate"];

    //     try{
    //         RequestParamValidator::validateRequestParamId($imageId);
            
    //         $isModerate = filter_var($isModerateRaw, FILTER_VALIDATE_BOOL);

    //         $this->imagesService->updateImageModerationByImageId($imageId, $isModerate);
            
    //         $_SESSION["success_message"] = "Image successfully ".($isModerate ? "moderated" : "unmoderated").".";
    //         header("Location: /images/details/$imageId");
    //     }
    //     catch(NotFoundException $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /portfolio");
    //     }
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //         header("Location: /images/details/$imageId");
    //     }
    // }

    // #[Route("GET", "/images/delete", ["id"])]
    // public function deleteImage(array $requestParams)
    // {
    //     $imageId = $requestParams["id"];
        
    //     try{
    //         RequestParamValidator::validateRequestParamId($imageId);
            
    //         $this->imagesService->deleteImageByImageId($imageId);
    //         $_SESSION["success_message"] = "Successfully deleted image.";
    //     } 
    //     catch(Exception $e){
    //         $_SESSION["error_message"] = $e->getMessage();
    //     } 

    //     header("Location: /portfolio");
    // }
}
