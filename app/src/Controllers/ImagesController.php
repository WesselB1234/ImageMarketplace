<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;
use App\Models\Image;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;

class ImagesController extends Controller
{
    private IImagesService $imagesService;

    public function __construct()
    {
        $this->loggedInAuthorization();

        $this->imagesService = new ImagesService();
    }

    public function index()
    {

    }

    public function details()
    {

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
        $this->imagesService->uploadImageFile("test");

        echo "Image uploaded successfully!";
    }

    public function moderateImage()
    {
        
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
