<?php 

namespace App\Services;

use App\Services\Interfaces\IImagesService;
use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\ImagesRepository;
use App\Models\Image;
use App\Models\User;
use Exception;

class ImagesService implements IImagesService
{
    private IImagesRepository $imagesRepository; 
    private const ALLOWED_IMAGE_TYPES = ["image/jpeg", "image/png"];

    public function __construct()
    {
        $this->imagesRepository = new ImagesRepository();
    }

    public function getAllImages(): array
    {
        return [];
    }

    public function getAllOnSaleImages(): array
    {
        return [];
    }

    public function getImageByImageId(int $imageId): ?Image
    {
        return null;
    }

    public function uploadImageFile(int $imageId)
    {
        if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != UPLOAD_ERR_OK) {
            throw new Exception("Upload failed.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES["image"]["tmp_name"]);

        if (!in_array($mimeType, $this::ALLOWED_IMAGE_TYPES)) {
            throw new Exception("Invalid image type.");
        }

        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $filename = strval($imageId).".".$extension;
        $destination = "assets/img/UserUploadedImages/$filename";

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
            throw new Exception("Failed to save image.");
        }
    }

    public function createImage(Image $image): int
    {
        return $this->imagesRepository->createImage($image);
    }

    public function buyImage(int $imageId, User $user)
    {
        return null;
    }

    public function sellImage(int $imageId, User $user)
    {
        return null;
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated)
    {
        return null;
    }

    public function deleteImageByImageId(int $id)
    {
        return null;
    }
}