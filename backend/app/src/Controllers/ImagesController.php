<?php

namespace App\Controllers;

use App\Mappers\DtoMapper;
use App\Models\Dtos\ModerateImageDto;
use App\Models\Dtos\SellImageDto;
use App\Models\Dtos\BuyImageDto;
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

    #[Route("GET", "/images/all-on-sale")]
    public function getOnSaleImages()
    {   
        $this->authenticationService->getLoggedInUser();
        $dtosArray = $this->imagesService->getAllOnSaleImages();

        http_response_code(200);
        echo json_encode($dtosArray, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/images/{id}")]
    public function getImageById(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"];

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

    #[Route("PATCH", "/images/{id}/sell")]
    public function sell(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"];    
        $data = $this->getDataFromInput(["price"]); 

        $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
        $this->imagesService->sellImage($image, $data["price"], $loggedInUser);

        $dto = new SellImageDto($imageId, $data["price"], true);
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("PATCH", "/images/{id}/take-off-sale")]
    public function takeOffSale(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"]; 
        
        $this->imagesService->takeImageOffSaleByImageId($imageId, $loggedInUser);

        $dto = new SellImageDto($imageId, null, false);
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("PATCH", "/images/{id}/buy")]
    public function buyImage(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"]; 
        
        $image = $this->imagesService->getImageByImageIdOrThrow($imageId);
        $this->imagesService->buyImage($image, $loggedInUser);

        $dto = new BuyImageDto($imageId, $loggedInUser->getUserId());
        header("Authorization: Bearer ". $this->authenticationService->generateAuthTokenFromUser($loggedInUser));
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("POST", "/images")]
    public function upload()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = null;

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

    #[Route("PATCH", "/images/{id}/moderate")]
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
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("DELETE", "/images/{id}")]
    public function deleteImage(array $requestParams)
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"];
    
        RequestParamValidator::validateRequestParamId($imageId);
        
        $this->imagesService->deleteImageByImageId($imageId, $loggedInUser);

        http_response_code(204);
    }
}
