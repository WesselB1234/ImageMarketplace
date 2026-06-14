<?php

namespace App\Services\Interfaces;

use App\Models\Dtos\BuyImageDto;
use App\Models\Dtos\SellImageDto;
use App\Models\Dtos\ModerateImageDto;
use App\Models\Dtos\ImageDto;
use App\Models\User;

interface IImagesService
{
    public function getAllImagesFromUserId(int $userId, ?int $page, ?int $pageSize): array;
    public function getAllOnSaleImages(?int $page, ?int $pageSize): array;
    public function getImageDtoById(int $imageId, User $loggedInUser): ImageDto;
    public function createImage(string $name, string $description, array $imageFile, string $altText, User $loggedInUser): ImageDto;
    public function buyImage(int $imageId, User $buyerUser): BuyImageDto;
    public function sellImage(int $imageId, int $price, User $sellerUser): SellImageDto;
    public function takeImageOffSaleByImageId(int $imageId, User $loggedInUser): SellImageDto;
    public function updateImageModerationByImageId(int $imageId, bool $isModerated): ModerateImageDto;
    public function deleteImageByImageId(int $imageId, User $loggedInUser);
}