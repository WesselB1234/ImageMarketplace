<?php 

namespace App\Services;

use App\Services\Interfaces\IImagesService;

class ImagesService implements IImagesService
{
    public function getAllImages(): array
    {
        return [];
    }

    public function getAllOnSaleImages(): array
    {
        return [];
    }

    public function getImageByImageId(int $imageId): ?Image
    {
        return null;
    }

    public function buyImage(int $imageId, User $user)
    {
        return null;
    }

    public function sellImage(int $imageId, User $user)
    {
        return null;
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated)
    {
        return null;
    }

    public function deleteImageByImageId(int $id)
    {
        return null;
    }
}