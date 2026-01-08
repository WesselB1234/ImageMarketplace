<?php

namespace App\Services\Interfaces;

use App\Models\User;
use App\Models\Image;

interface IImagesService
{
    public function getAllImagesFromUserId(int $userId): array;
    public function getAllOnSaleImages(): array;
    public function getImageByImageId(int $imageId): ?Image;
    public function getImageByImageIdOrThrow(int $imageId): Image;
    public function createImage(Image $image): int;
    public function uploadImageFile(int $imageId);
    public function buyImage(Image $image);
    public function sellImage(Image $image, int $price);
    public function takeImageOffSaleByImageId(int $imageId);
    public function updateImageSellingPrice(int $imageId, ?int $price);
    public function updateImageModerationByImageId(int $imageId, bool $isModerated);
    public function deleteImageByImageId(int $imageId);
    public function isUserAuthorizedToImage(Image $image): bool;
}