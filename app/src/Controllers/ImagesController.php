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
        $images = $this->imagesService->getAllOnSaleImages();
        
        $this->displayView("Images/index.php", ["viewModel" => $images]);
    }

    public function details(array $vars)
    {
        $imageId = $vars["id"];

        try{
            if (!isset($imageId)){
                throw new Exception("Image ID cannot be empty.");
            }

            $image = $this->imagesService->getImageByImageId($imageId);

            if ($image == null){
                throw new Exception("Image with ID $imageId does not exist.");
            }

            $this->displayView("Images/details.php", ["viewModel" => $image]);
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
        $image = Image::constructUnknownImage($_SESSION["user"]->userId, $_POST["name"], $_POST["description"], $_POST["alt_text"]);
        
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
