<?php  

namespace App\Services;

use App\Mappers\DtoMapper;
use App\Models\Dtos\BuyImageDto;
use App\Models\Dtos\ImageDto;
use App\Models\Dtos\ModerateImageDto;
use App\Models\Dtos\SellImageDto;
use App\Models\User;
use App\Services\Interfaces\IImagesService;
use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\Image;
use DateTime;
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
        $images = $this->imagesRepository->getAllImagesFromUserId($userId);
        
        return DtoMapper::mapImagesArrayToDtoList($images);
    }

    public function getAllOnSaleImages(): array
    {
        $images = $this->imagesRepository->getAllOnSaleImages();

        return DtoMapper::mapImagesArrayToDtoList($images);
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

    public function getImageDtoById(int $imageId, User $loggedInUser): ImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);
        
        if ($image->getIsOnSale() === false && $loggedInUser->getRole() !== UserRole::Admin && $image->getOwnerId() !== $loggedInUser->getUserId()){
            throw new NotAuthorizedException("You cannot view private off sale images.");
        }

        if ($image->getOwnerId() !== null){
            $image->setOwner($this->usersRepository->getUserByUserId($image->getOwnerId()));
        }

        if ($image->getCreatorId() !== null){
            $image->setCreator($this->usersRepository->getUserByUserId($image->getCreatorId()));
        }
        
        return DtoMapper::mapImageToDto($image);
    }

    public function validateImageFile(array $imageFile)
    {    
        if (!isset($imageFile) || $imageFile["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("The uploading of the file to the server has failed.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $imageFile["tmp_name"]);

        if ($mimeType !== "image/png") {
            throw new Exception("You cannot use any other image extension other than .png");
        }
    }

    public function uploadImageFile(array $imageFile, int $imageId)
    {
        $extension = pathinfo($imageFile["name"], PATHINFO_EXTENSION);
        $filename = strval($imageId).".".$extension;
        $destination = "assets/img/UserUploadedImages/$filename";

        if (!move_uploaded_file($imageFile["tmp_name"], $destination)) {
            throw new Exception("Failed to save image to file structure.");
        }
    }

    public function createImage(string $name, string $description, array $imageFile, string $altText, User $loggedInUser): ImageDto
    {
        $imageId = null;
        $image = Image::constructUnknownImage($loggedInUser->getUserId(), $loggedInUser->getUserId(), $name, $description, $altText);

        try{
            $this->validateImageFile($imageFile);
            $imageId = $this->imagesRepository->createImage($image);
            $this->uploadImageFile($imageFile, $imageId);
            
            $image->setImageId($imageId);
            $image->setTimeCreated(New DateTime());

            return DtoMapper::mapImageToDto($image);
        }
        catch(Exception $e){

            if ($imageId !== null){
                $this->deleteImageByImageId($imageId, $loggedInUser);       
            }
            
            throw $e;
        }
    }

    public function buyImage(int $imageId, User $buyerUser): BuyImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        if ($image->getOwnerId() === $buyerUser->getUserId()){
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

        return new BuyImageDto($image->getImageId(), $buyerUser->getUserId());
    }

    public function sellImage(int $imageId, int $price, User $loggedInUser): SellImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        if (!$this->isUserAuthorizedToImage($image, $loggedInUser)){
            throw new NotAuthorizedException("You are not authorized to sell this image.");
        }

        if ($price < 0){
            throw new Exception("Price cannot be negative");
        }
        
        $this->imagesRepository->updateImageSellingPrice($image->getImageId(), $price);

        return new SellImageDto($imageId, $price, true);
    }

    public function takeImageOffSaleByImageId(int $imageId, User $loggedInUser): SellImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        if (!$this->isUserAuthorizedToImage($image, $loggedInUser)){
            throw new NotAuthorizedException("You are not authorized to take this image off sale.");
        }

        $this->updateImageSellingPrice($image->getImageId(), null);

        return new SellImageDto($imageId, null, false);
    }

    public function updateImageSellingPrice(int $imageId, ?int $price)
    {
        $this->imagesRepository->updateImageSellingPrice($imageId, $price);
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated): ModerateImageDto
    {
        $this->imagesRepository->updateImageModerationByImageId($imageId, $isModerated);

        return new ModerateImageDto($imageId, $isModerated);
    }

    public function deleteImageByImageId(int $imageId, User $loggedInUser)
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        if (!$this->isUserAuthorizedToImage($image, $loggedInUser)){
            throw new NotAuthorizedException("You are not authorized to delete this image.");
        }

        return $this->imagesRepository->deleteImageByImageId($imageId);
    }

    public function isUserAuthorizedToImage(Image $image, User $loggedInUser): bool
    {
        if ($image->getOwnerId() !== $loggedInUser->getUserId() && $loggedInUser->getRole() !== UserRole::Admin){
            return false;
        }

        return true;
    }
}
