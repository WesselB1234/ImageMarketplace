<?php

namespace App\Services\Interfaces;

use App\Models\Dtos\BuyImageDto;
use App\Models\Dtos\SellImageDto;
use App\Models\Dtos\ImageDto;
use App\Models\Image;
use App\Models\User;

interface IImagesService
{
    public function getAllImagesFromUserId(int $userId): array;
    public function getAllOnSaleImages(): array;
    public function getImageByImageId(int $imageId): ?Image;
    public function getImageDtoById(int $imageId, User $loggedInUser): ImageDto;
    public function getImageByImageIdOrThrow(int $imageId): Image;
    public function createImage(string $name, string $description, array $imageFile, string $altText, User $loggedInUser): ImageDto;
    public function validateImageFile(array $imageFile);
    public function uploadImageFile(array $imageFile, int $imageId);
    public function buyImage(int $imageId, User $buyerUser): BuyImageDto;
    public function sellImage(int $imageId, int $price, User $loggedInUser): SellImageDto;
    public function takeImageOffSaleByImageId(int $imageId, User $loggedInUser);
    public function updateImageSellingPrice(int $imageId, ?int $price);
    public function updateImageModerationByImageId(int $imageId, bool $isModerated);
    public function deleteImageByImageId(int $imageId, User $loggedInUser);
    public function isUserAuthorizedToImage(Image $image, User $loggedInUser): bool;
}