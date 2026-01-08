<?php 

namespace App\Services;

use App\Services\Interfaces\IImagesService;
use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\ImagesRepository;
use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\UsersRepository;
use App\Models\Image;
use App\Models\User;
use Exception;

class ImagesService implements IImagesService
{
    private IImagesRepository $imagesRepository; 
    private IUsersRepository $usersRepository; 

    public function __construct()
    {
        $this->imagesRepository = new ImagesRepository();
        $this->usersRepository = new UsersRepository();
    }

    public function getAllImagesFromUserId(int $userId): array
    {
        return $this->imagesRepository->getAllImagesFromUserId($userId);
    }

    public function getAllOnSaleImages(): array
    {
        return $this->imagesRepository->getAllOnSaleImages();
    }

    public function getImageByImageId(int $imageId): ?Image
    {
        return $this->imagesRepository->getImageByImageId($imageId);
    }

    public function uploadImageFile(int $imageId)
    {
        if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("The uploading of the file to the server has failed.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES["image"]["tmp_name"]);

        if ($mimeType !== "image/png") {
            throw new Exception("You cannot use any other image extension other than .png");
        }

        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $filename = strval($imageId).".".$extension;
        $destination = "assets/img/UserUploadedImages/$filename";

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
            throw new Exception("Failed to save image to file structure.");
        }
    }

    public function createImage(Image $image): int
    {
        return $this->imagesRepository->createImage($image);
    }

    public function buyImage(Image $image, User $buyerUser)
    {
        if ($image->isOnSale === false || $image->isModerated || $image->price === null){
            throw new Exception("This image is not on sale");
        }

        if ($image->price > $buyerUser->imageTokens){
            throw new Exception("You do not have e nough image tokens to purchase this image.");
        }

        $ownerUser = $this->usersRepository->getUserByUserId($image->ownerId);
        $buyerUser->imageTokens = $buyerUser->imageTokens - $image->price;

        if($ownerUser !== null){
            $ownerUser->imageTokens = $ownerUser->imageTokens + $image->price;
            $this->usersRepository->updateTokensBalanceByUserId($ownerUser->userId, $ownerUser->imageTokens);
        }

        $this->usersRepository->updateTokensBalanceByUserId($buyerUser->userId, $buyerUser->imageTokens);
        $this->imagesRepository->updateImageOwnershipByImageId($image->imageId, $buyerUser->userId);
    }

    public function updateImageSellingPrice(int $imageId, ?int $price)
    {
        $this->imagesRepository->updateImageSellingPrice($imageId, $price);
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated)
    {
        $this->imagesRepository->updateImageModerationByImageId($imageId, $isModerated);
    }

    public function deleteImageByImageId(int $imageId)
    {
        return $this->imagesRepository->deleteImageByImageId($imageId);
    }
}