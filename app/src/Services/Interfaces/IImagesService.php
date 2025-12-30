<?php

namespace App\Services\Interfaces;

use App\Models\User;
use App\Models\Image;

interface IImagesService
{
    public function getAllImages(): array;
    public function getAllOnSaleImages(): array;
    public function getImageByImageId(int $imageId): ?Image;
    public function createImage(Image $image): int;
    public function uploadImageFile(int $imageId);
    public function buyImage(int $imageId, User $user);
    public function sellImage(int $imageId, User $user);
    public function updateImageModerationByImageId(int $imageId, bool $isModerated);
    public function deleteImageByImageId(int $id);
}