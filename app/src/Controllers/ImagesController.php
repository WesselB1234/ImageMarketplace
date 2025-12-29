<?php

namespace App\Controllers;

use App\Controllers\Controller;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->loggedInAuthorization();
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
        // 1. Check file exists
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            die('Upload failed.');
        }

        // 2. Validate image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            die('Invalid image type.');
        }

        $uploadDir = __DIR__."../../assets/img/UserUploadedImages";

        // 4. Generate safe filename
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . $extension;

        // 5. Move uploaded file
        $destination = "assets/img/UserUploadedImages/$filename";

        var_dump($_FILES);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            die('Failed to save image.');
        }

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
