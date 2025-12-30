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

    public function __construct()
    {
        $this->imagesRepository = new ImagesRepository();
    }

    public function getAllImagesFromUserId(int $userId): array
    {
        return $this->imagesRepository->getAllImagesFromUserId($userId);
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

        if ($mimeType != "image/png") {
            throw new Exception("You cannot use any other image extension other than .png");
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

    public function deleteImageByImageId(int $imageId)
    {
        return $this->imagesRepository->deleteImageByImageId($imageId);
    }
}