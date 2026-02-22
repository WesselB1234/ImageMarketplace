<?php

namespace App\Repositories\Interfaces;

use App\Models\Image;

interface IImagesRepository
{
    public function getAllImagesFromUserId(int $userId): array;
    public function getAllOnSaleImages(): array;
    public function getImageByImageId(int $imageId): ?Image;
    public function createImage(Image $image): int;
    public function updateImageSellingPrice(int $imageId, ?int $price);
    public function updateImageOwnershipByImageId(int $imageId, int $userId);
    public function updateImageModerationByImageId(int $imageId, bool $isModerated);
    public function deleteImageByImageId(int $imageId);
}