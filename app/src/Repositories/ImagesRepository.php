<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\Repository;
use App\Models\Image;
use App\Models\Helpers\DataMapper;

use PDO;

class ImagesRepository extends Repository implements IImagesRepository
{
    public function getAllImagesFromUserId(int $userId): array
    {
        $images = [];

        $stmt = $this->connection->prepare(
            "SELECT id, owner_id, name, description, price, is_moderated, is_onsale, time_created, alt_text 
            FROM Images
            WHERE owner_id = :userId;"
        );

        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT); 
        $stmt->execute();

        $assocImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($assocImages as $assocImage){
            array_push($images, DataMapper::mapAssocImageToImage($assocImage));
        }

        return $images;
    }

    function getAllOnSaleImages(): array
    {
        $images = [];

        $stmt = $this->connection->prepare(
            "SELECT id, owner_id, name, description, price, is_moderated, is_onsale, time_created, alt_text 
            FROM Images
            WHERE is_onsale = 1;"
        );

        $stmt->execute();

        $assocImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($assocImages as $assocImage){
            array_push($images, DataMapper::mapAssocImageToImage($assocImage));
        }

        return $images;
    }

    public function getImageByImageId(int $imageId): ?Image
    {
         
    }

    public function updateImage(Image $image)
    {
        return null;
    }

    public function createImage(Image $image): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO Images(owner_id, name, description, price, is_moderated, is_onsale, alt_text) 
            VALUES (:ownerId, :name, :description, :price, :isModerated, :isOnsale, :altText);"
        );

        $stmt->bindValue(":ownerId", $image->ownerId, PDO::PARAM_INT); 
        $stmt->bindValue(":name", $image->name, PDO::PARAM_STR); 
        $stmt->bindValue(":description", $image->description, PDO::PARAM_STR); 
        $stmt->bindValue(":price", null, PDO::PARAM_NULL); 
        $stmt->bindValue(":isModerated", $image->isModerated, PDO::PARAM_BOOL);
        $stmt->bindValue(":isOnsale", $image->isOnSale, PDO::PARAM_BOOL);
        $stmt->bindValue(":altText", $image->altText, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$this->connection->lastInsertId();
    }

    public function updateImageOwnershipByImageId(int $imageId, int $userId)
    {
        return null;
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated)
    {
        return null;
    }

    public function deleteImageByImageId(int $imageId)
    {
        $stmt = $this->connection->prepare("DELETE FROM Images WHERE id = :imageId");
        $stmt->bindValue(":imageId", $imageId, PDO::PARAM_INT); 
        $stmt->execute();

        if($stmt->rowCount() == 0)
        {
            throw new NotFoundException("User with id ".$userId." does not exist.");
        }
    }
}