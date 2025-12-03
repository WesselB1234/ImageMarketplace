<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\Repository;

class ImagesRepository extends Repository implements IImagesRepository
{
    public function getAllImages(): array
    {
        return [];
    }

    function getAllOnSaleImages(): array
    {
        return [];
    }

    public function getImageByImageId(int $imageId): ?Image
    {
        return null;
    }

    public function updateImage(Image $image)
    {
        return null;
    }

    public function updateImageOwnershipByImageId(int $imageId, int $userId)
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