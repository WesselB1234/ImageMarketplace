<?php

namespace App\Controllers;

use App\Mappers\DtoMapper;
use App\Models\Dtos\ModerateImageDto;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IImagesService;
use App\Services\Interfaces\IUsersService;
use App\Models\Image;
use App\Models\Enums\UserRole;
use DateTime;
use Exception;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Attributes\Route;
use App\Models\Helpers\RequestParamValidator;

class ImagesController extends ApiController
{
    private IImagesService $imagesService;
    private IUsersService $usersService;
    private IAuthenticationService $authenticationService;

    public function __construct(IImagesService $imagesService, IUsersService $usersService, IAuthenticationService $authenticationService)
    {
        $this->imagesService = $imagesService;
        $this->usersService = $usersService;
        $this->authenticationService = $authenticationService;
    }

    // #[Route("GET", "/images")]
    // public function index()
    // {
    //     $images = $this->imagesService->getAllOnSaleImages();
        
    //     $this->displayView(["viewModel" => $images], "Images/index.php");
    // }

    #[Route("GET", "/images", ["id"])]
    public function getImageById(array $requestParams)
    {
        $imageId = $requestParams["id"];
        $loggedInUser = $this->authenticationService->getLoggedInUser();
            
        RequestParamValidator::validateRequestParamId($imageId);

        $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
        
        if ($image->getIsOnSale() === false && $loggedInUser->getRole() !== UserRole::Admin && $image->getOwnerId() !== $loggedInUser->getUserId()){
            throw new NotAuthorizedException("You cannot view private off sale images.");
        }

        if ($image->getOwnerId() !== null){
            $image->setOwner($this->usersService->getUserByUserId($image->getOwnerId()));
        }

        if ($image->getCreatorId() !== null){
            $image->setCreator($this->usersService->getUserByUserId($image->getCreatorId()));
        }
        
        $imageDto = DtoMapper::mapImageToDto($image);
            
        http_response_code(200);
        echo json_encode($imageDto);
    }

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
        $imageId = null;
        $loggedInUser = $this->authenticationService->getLoggedInUser();

        $data = $this->getDataFromInput(["name", "description", "image", "altText"]);
        $image = Image::constructUnknownImage($loggedInUser->getUserId(), $loggedInUser->getUserId(), $data["name"], $data["description"], $data["altText"]);
        
        $imageFile = $data["image"];

        try{
            $this->imagesService->validateImageFile($imageFile);
            $imageId = $this->imagesService->createImage($image);
            $this->imagesService->uploadImageFile($imageFile, $imageId);
            $image->setImageId($imageId);
            $image->setTimeCreated(New DateTime());

            $imageDto = DtoMapper::mapImageToDto($image);
            
            http_response_code(201);
            echo json_encode($imageDto);
        }
        catch(Exception $e){

            if ($imageId !== null){
                $this->imagesService->deleteImageByImageId($imageId, $loggedInUser);       
            }
            
            throw $e;
        }               
    }

    #[Route("PATCH", "/images/moderate", ["id"])]
    public function moderateImage(array $requestParams)
    {
        $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        $data = $this->getDataFromInput(["isModerate"]);

        $imageId = $requestParams["id"];
        $isModerateRaw = $data["isModerate"];

        RequestParamValidator::validateRequestParamId($imageId);
        
        $isModerate = filter_var($isModerateRaw, FILTER_VALIDATE_BOOL);

        $this->imagesService->updateImageModerationByImageId($imageId, $isModerate);
        
        $dto = new ModerateImageDto($imageId, $isModerate);
        http_response_code(201);
        echo json_encode($dto);
    }

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
