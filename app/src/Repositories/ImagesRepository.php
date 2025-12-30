<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\Repository;
use App\Models\Image;

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

    public function createImage(Image $image): int
    {
        // $stmt = $this->connection->prepare(
        //     "INSERT INTO Users (username, email, password, image_tokens, role) 
        //     VALUES (:username, :email, :password, :image_tokens, :role);"
        // );

        // $stmt->bindValue(":username", $user->username, PDO::PARAM_STR); 
        // $stmt->bindValue(":email", $user->email, PDO::PARAM_STR); 
        // $stmt->bindValue(":password", $user->password, PDO::PARAM_STR); 
        // $stmt->bindValue(":image_tokens", $user->imageTokens, PDO::PARAM_INT); 
        // $stmt->bindValue(":role", $user->role->value, PDO::PARAM_STR);

        // $stmt->execute();

        return 1;
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