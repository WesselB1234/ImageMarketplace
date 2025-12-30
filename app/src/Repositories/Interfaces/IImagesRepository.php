<?php

namespace App\Repositories\Interfaces;

use App\Models\Image;

interface IImagesRepository
{
    public function getAllImages(): array;
    public function getAllOnSaleImages(): array;
    public function getImageByImageId(int $imageId): ?Image;
    public function createImage(Image $image): int;
    public function updateImage(Image $image);
    public function updateImageOwnershipByImageId(int $imageId, int $userId);
    public function updateImageModerationByImageId(int $imageId, bool $isModerated);
    public function deleteImageByImageId(int $imageId);
}