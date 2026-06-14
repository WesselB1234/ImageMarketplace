<?php

namespace App\Controllers;

use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IImagesService;
use App\Models\Enums\UserRole;
use App\Models\Attributes\Route;
use App\Utils\JwtUtil;

class ImagesController extends ApiController
{
    private IImagesService $imagesService;
    private IAuthenticationService $authenticationService;
    private JwtUtil $jwtUtil;

    public function __construct(IImagesService $imagesService, IAuthenticationService $authenticationService, JwtUtil $jwtUtil)
    {
        $this->imagesService = $imagesService;
        $this->authenticationService = $authenticationService;
        $this->jwtUtil = $jwtUtil;
    }

    #[Route("GET", "/images/all-on-sale")]
    public function getOnSaleImages()
    {   
        $this->authenticationService->getLoggedInUser();
        $dtosArray = $this->imagesService->getAllOnSaleImages(empty($_GET["page"]) ? null : $_GET["page"] , empty($_GET["pageSize"]) ? null : $_GET["pageSize"]);

        http_response_code(200);
        echo json_encode($dtosArray, JSON_PRETTY_PRINT);
    }

    #[Route("GET", "/images/{id}")]
    public function getImageById(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"];
        
        $imageDto = $this->imagesService->getImageDtoById($imageId, $loggedInUser);
            
        http_response_code(200);
        echo json_encode($imageDto);
    }

    #[Route("PATCH", "/images/{id}/sell")]
    public function sell(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"];    
        $data = $this->getDataFromInput(["price"]); 

        $dto = $this->imagesService->sellImage($imageId, $data["price"], $loggedInUser);
        
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("PATCH", "/images/{id}/take-off-sale")]
    public function takeOffSale(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"]; 
        
        $dto = $this->imagesService->takeImageOffSaleByImageId($imageId, $loggedInUser);
        
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("PATCH", "/images/{id}/buy")]
    public function buyImage(array $requestParams)
    {   
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"]; 
        
        $dto = $this->imagesService->buyImage($imageId, $loggedInUser);

        header("Authorization: Bearer ". $this->jwtUtil->generateAuthTokenFromUser($loggedInUser));
        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("POST", "/images")]
    public function upload()
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $data = $this->getDataFromInput(["name", "description", "image", "altText"]);

        $dto = $this->imagesService->createImage($data["name"], $data["description"], $data["image"], $data["altText"], $loggedInUser);

        http_response_code(201);
        echo json_encode($dto);               
    }

    #[Route("PATCH", "/images/{id}/moderate")]
    public function moderateImage(array $requestParams)
    {
        $this->authenticationService->getLoggedInUserByRoleAuthorization([UserRole::Admin]);
        $imageId = $requestParams["id"];
        $data = $this->getDataFromInput(["isModerate"]);
        $isModerate = filter_var($data["isModerate"], FILTER_VALIDATE_BOOL);

        $dto = $this->imagesService->updateImageModerationByImageId($imageId, $isModerate);

        http_response_code(200);
        echo json_encode($dto);
    }

    #[Route("DELETE", "/images/{id}")]
    public function deleteImage(array $requestParams)
    {
        $loggedInUser = $this->authenticationService->getLoggedInUser();
        $imageId = $requestParams["id"];
        
        $this->imagesService->deleteImageByImageId($imageId, $loggedInUser);

        http_response_code(204);
    }
}
