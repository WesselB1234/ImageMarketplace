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
use App\Models\Exceptions\NotFoundException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Enums\UserRole;

class ImagesService implements IImagesService
{
    private IImagesRepository $imagesRepository; 
    private IUsersRepository $usersRepository; 

    public function __construct(IImagesRepository $imagesRepository, IUsersRepository $usersRepository)
    {
        $this->imagesRepository = $imagesRepository;
        $this->usersRepository = $usersRepository;
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

    public function getImageByImageIdOrThrow(int $imageId): Image
    {
        $image = $this->getImageByImageId($imageId);

        if ($image === null){
            throw new NotFoundException("Image with ID ".$imageId." does not exist.");
        }

        return $image;
    }

    public function vali

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

    public function buyImage(Image $image)
    {
        $buyerUser = $_SESSION["user"];

        if ($image->getOwnerId() === $_SESSION["user"]->getUserId()){
            throw new NotAuthorizedException("You cannot buy your own image.");
        }

        if ($image->getIsOnSale() === false || $image->getIsModerated() || $image->getPrice() === null){
            throw new Exception("This image is not on sale");
        }

        if ($image->getPrice() > $buyerUser->getImageTokens()){
            throw new Exception("You do not have enough image tokens to purchase this image.");
        }

        $ownerUser = $this->usersRepository->getUserByUserId($image->getOwnerId());
        $buyerUser->setImageTokens($buyerUser->getImageTokens() - $image->getPrice());

        if($ownerUser !== null){
            $ownerUser->setImageTokens($ownerUser->getImageTokens() + $image->getPrice());
            $this->usersRepository->updateTokensBalanceByUserId($ownerUser->getUserId(), $ownerUser->getImageTokens());
        }

        $this->usersRepository->updateTokensBalanceByUserId($buyerUser->getUserId(), $buyerUser->getImageTokens());
        $this->imagesRepository->updateImageOwnershipByImageId($image->getImageId(), $buyerUser->getUserId());
    }

    public function sellImage(Image $image, int $price)
    {
        if (!$this->isUserAuthorizedToImage($image)){
            throw new NotAuthorizedException("You are not authorized to sell this image.");
        }

        if ($price < 0){
            throw new Exception("Price cannot be negative");
        }
        
        $this->imagesRepository->updateImageSellingPrice($image->getImageId(), $price);
    }

    public function takeImageOffSaleByImageId(int $imageId)
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        if (!$this->isUserAuthorizedToImage($image)){
            throw new NotAuthorizedException("You are not authorized to take this image off sale.");
        }

        $this->updateImageSellingPrice($image->getImageId(), null);
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
        $image = $this->getImageByImageIdOrThrow($imageId);

        if (!$this->isUserAuthorizedToImage($image)){
            throw new NotAuthorizedException("You are not authorized to delete this image.");
        }

        return $this->imagesRepository->deleteImageByImageId($imageId);
    }

    public function isUserAuthorizedToImage(Image $image): bool
    {
        if ($image->getOwnerId() !== $_SESSION["user"]->getUserId() && $_SESSION["user"]->getRole() !== UserRole::Admin){
            return false;
        }

        return true;
    }
}
