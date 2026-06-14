<?php 

namespace App\Policies;

use App\Models\Enums\UserRole;
use App\Exception\NotAuthorizedException;
use App\Exception\UploadException;
use App\Models\Image;
use App\Models\User;
use InvalidArgumentException;

class ImagesPolicy
{
    public function enforceSellImage(Image $image, User $sellerUser, int $price) 
    {
        $this->enforceUserIsAuthorizedToConfigureImage($image, $sellerUser);
        $this->enforcePriceCannotBeNegative($price);
    }

    public function enforceBuyImage(Image $image, User $buyerUser) 
    {
        $this->enforceCannotBuyOwnImage($image, $buyerUser);
        $this->enforceImageIsOnSale($image);
        $this->enforceUserHasEnoughImageTokens($image, $buyerUser);
    }

    public function enforceUserIsAuthorizedToConfigureImage(Image $image, User $user): bool
    {
        if ($image->getOwnerId() !== $user->getUserId() && $user->getRole() !== UserRole::Admin){
            return false;
        }

        return true;
    }

    public function enforceIncomingImageFile(array $imageFile)
    {    
        if (!isset($imageFile) || $imageFile["error"] !== UPLOAD_ERR_OK) {
            throw new UploadException("The uploading of the file to the server has failed.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $imageFile["tmp_name"]);

        if ($mimeType !== "image/png") {
            throw new InvalidArgumentException("You cannot use any other image extension other than .png");
        }
    }

    public function enforceAuthorizedToViewImage(Image $image, User $user) 
    {
        if ($image->getIsOnSale() === false && $user->getRole() !== UserRole::Admin && $image->getOwnerId() !== $user->getUserId()){
            throw new NotAuthorizedException("You cannot view private off sale images.");
        }
    }

    private function enforcePriceCannotBeNegative(int $price) 
    {
        if ($price < 0){
            throw new InvalidArgumentException("Price cannot be negative");
        }
    }

    private function enforceCannotBuyOwnImage(Image $image, User $buyerUser)
    {
        if ($image->getOwnerId() === $buyerUser->getUserId()){
            throw new InvalidArgumentException("You cannot buy your own image.");
        }
    }

    private function enforceImageIsOnSale(Image $image) 
    {
        if ($image->getIsOnSale() === false || $image->getIsModerated() || $image->getPrice() === null){
            throw new InvalidArgumentException("This image is not on sale");
        }
    }

    private function enforceUserHasEnoughImageTokens(Image $image, User $buyerUser) 
    {
        if ($image->getPrice() > $buyerUser->getImageTokens()){
            throw new InvalidArgumentException("You do not have enough image tokens to purchase this image.");
        }
    }
}
