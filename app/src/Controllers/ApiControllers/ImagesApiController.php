<?php

namespace App\Controllers\ApiControllers;

use App\Controllers\ApiControllers\ApiController;
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;
use Exception;

class ImagesApiController extends ApiController
{
    private IImagesService $imagesService;

    public function __construct()
    {
        parent::__construct();

        $this->imagesService = new ImagesService();
    }

    public function getOnSaleImages()
    {
        try{
            $this->loggedInAuthorization();   
            
            $images = $this->imagesService->getAllOnSaleImages();

            http_response_code(200); 
            echo json_encode($images, JSON_PRETTY_PRINT);
            exit;
        }
        catch(NotAuthorizedException $e){
            http_response_code(401); 
        }
        catch(Exception $e){
            http_response_code(400); 
        }  

        $this->displayErrorJson($e->getMessage());
    }
} 