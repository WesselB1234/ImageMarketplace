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
        
        $this->loggedInAuthorization();
    }

    public function getOnSaleImages()
    {
        try{            
            $images = $this->imagesService->getAllOnSaleImages();

            http_response_code(200); 
            echo json_encode($images, JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $this->displayErrorJson(400, $e->getMessage());
        }  
    }
} 