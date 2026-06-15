<?php  

namespace App\Services;

use App\Mappers\ImagesMapper;
use App\Models\Dtos\BuyImageDto;
use App\Models\Dtos\ImageDto;
use App\Models\Dtos\ModerateImageDto;
use App\Models\Dtos\SellImageDto;
use App\Models\User;
use App\Policies\ImagesPolicy;
use App\Services\Interfaces\IImagesService;
use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\Image;
use DateTime;
use Exception;
use App\Exception\NotFoundException;
use RuntimeException;

class ImagesService implements IImagesService
{
    private IImagesRepository $imagesRepository; 
    private IUsersRepository $usersRepository; 
    private ImagesPolicy $imagesPolicy;

    public function __construct(IImagesRepository $imagesRepository, IUsersRepository $usersRepository, ImagesPolicy $imagesPolicy)
    {
        $this->imagesRepository = $imagesRepository;
        $this->usersRepository = $usersRepository;
        $this->imagesPolicy = $imagesPolicy;
    }

    public function getAllImagesFromUserId(int $userId, ?int $page, ?int $pageSize): array
    {
        $images = $this->imagesRepository->getAllImagesFromUserId($userId, $page, $pageSize);
        
        return ImagesMapper::mapImagesArrayToDtoList($images);
    }

    public function getAllOnSaleImages(?int $page, ?int $pageSize): array
    {
        $images = $this->imagesRepository->getAllOnSaleImages($page, $pageSize);

        return ImagesMapper::mapImagesArrayToDtoList($images);
    }

    private function getImageByImageIdOrThrow(int $imageId): Image
    {
        $image = $this->imagesRepository->getImageByImageId($imageId);

        if ($image === null){
            throw new NotFoundException("Image with ID ".$imageId." does not exist.");
        }

        return $image;
    }

    public function getImageDtoById(int $imageId, User $loggedInUser): ImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);
        $this->imagesPolicy->enforceAuthorizedToViewImage($image, $loggedInUser);

        if ($image->getOwnerId() !== null){
            $image->setOwner($this->usersRepository->getUserByUserId($image->getOwnerId()));
        }

        if ($image->getCreatorId() !== null){
            $image->setCreator($this->usersRepository->getUserByUserId($image->getCreatorId()));
        }
        
        return ImagesMapper::mapImageToDto($image);
    }

    private function uploadImageFile(array $imageFile, int $imageId)
    {
        $extension = pathinfo($imageFile["name"], PATHINFO_EXTENSION);
        $filename = strval($imageId).".".$extension;
        $destination = "assets/img/UserUploadedImages/$filename";

        if (!is_dir("assets")){
            mkdir("assets");
        }
        
        if (!is_dir("assets/img")){
            mkdir("assets/img");
        }

        if (!is_dir("assets/img/UserUploadedImages")){
            mkdir("assets/img/UserUploadedImages");
        }

        if (!move_uploaded_file($imageFile["tmp_name"], $destination)) {
            throw new RuntimeException("Failed to save image to file structure.");
        }
    }

    public function createImage(string $name, string $description, array $imageFile, string $altText, User $loggedInUser): ImageDto
    {
        $imageId = null;
        $image = Image::constructUnknownImage($loggedInUser->getUserId(), $loggedInUser->getUserId(), $name, $description, $altText);

        try{
            $this->imagesPolicy->enforceIncomingImageFile($imageFile);
            $imageId = $this->imagesRepository->createImage($image);
            $this->uploadImageFile($imageFile, $imageId);
            
            $image->setImageId($imageId);
            $image->setTimeCreated(New DateTime());

            return ImagesMapper::mapImageToDto($image);
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
        $this->imagesPolicy->enforceBuyImage($image, $buyerUser);

        $ownerUser = $this->usersRepository->getUserByUserId($image->getOwnerId());

        if($ownerUser !== null){
            $this->usersRepository->incrementBalanceByUserId($ownerUser->getUserId(), $image->getPrice());
        }

        $buyerUser->setImageTokens($buyerUser->getImageTokens() - $image->getPrice());

        $this->usersRepository->decrementBalanceByUserId($buyerUser->getUserId(), $image->getPrice());
        $this->imagesRepository->updateImageOwnershipByImageId($image->getImageId(), $buyerUser->getUserId());

        return new BuyImageDto($image->getImageId(), $buyerUser->getUserId());
    }

    public function sellImage(int $imageId, int $price, User $sellerUser): SellImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        $this->imagesPolicy->enforceSellImage($image, $sellerUser, $price);
        $this->imagesRepository->updateImageSellingPrice($image->getImageId(), $price);

        return new SellImageDto($imageId, $price, true);
    }

    public function takeImageOffSaleByImageId(int $imageId, User $loggedInUser): SellImageDto
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        $this->imagesPolicy->enforceUserIsAuthorizedToConfigureImage($image, $loggedInUser);
        $this->imagesRepository->updateImageSellingPrice($image->getImageId(), null);

        return new SellImageDto($imageId, null, false);
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated): ModerateImageDto
    {
        $this->imagesRepository->updateImageModerationByImageId($imageId, $isModerated);

        return new ModerateImageDto($imageId, $isModerated);
    }

    public function deleteImageByImageId(int $imageId, User $loggedInUser)
    {
        $image = $this->getImageByImageIdOrThrow($imageId);

        $this->imagesPolicy->enforceUserIsAuthorizedToConfigureImage($image, $loggedInUser);

        return $this->imagesRepository->deleteImageByImageId($imageId);
    }
}
