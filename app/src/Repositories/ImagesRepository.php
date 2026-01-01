<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IImagesRepository;
use App\Repositories\Repository;
use App\Models\Image;
use App\Models\Helpers\DataMapper;
use App\Models\Exceptions\NotFoundException;

use PDO;

class ImagesRepository extends Repository implements IImagesRepository
{
    public function getAllImagesFromUserId(int $userId): array
    {
        $images = [];

        $stmt = $this->connection->prepare(
            "SELECT id, owner_id, name, creator_id, description, price, is_moderated, is_onsale, time_created, alt_text 
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
            "SELECT id, owner_id, creator_id, name, description, price, is_moderated, is_onsale, time_created, alt_text 
            FROM Images
            WHERE is_onsale = 1 AND is_moderated = 0; AND price IS NOT NULL;"
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
         $stmt = $this->connection->prepare(
            "SELECT id, owner_id, creator_id, name, description, price, is_moderated, is_onsale, time_created, alt_text 
            FROM Images
            WHERE id = :imageId;"
        );

        $stmt->bindValue(":imageId", $imageId, PDO::PARAM_INT); 
        $stmt->execute();

        $assocImage = $stmt->fetch(PDO::FETCH_ASSOC);

        if($assocImage !== false){
            return DataMapper::mapAssocImageToImage($assocImage);
        }

        return null;
    }

    public function updateImageSellingPrice(int $imageId, ?int $price)
    {
        $stmt = $this->connection->prepare(
            "UPDATE Images 
            SET price = :price, is_onsale = :isOnSale
            WHERE id = :imageId;"
        );

        $stmt->bindValue(":imageId", $imageId, PDO::PARAM_INT); 

        if ($price === null) {
            $stmt->bindValue(":price", null, PDO::PARAM_NULL); 
            $stmt->bindValue(":isOnSale", false, PDO::PARAM_BOOL); 
        }
        else{
            $stmt->bindValue(":price", $price, PDO::PARAM_STR); 
            $stmt->bindValue(":isOnSale", true, PDO::PARAM_BOOL); 
        }

        $stmt->execute();

        if($stmt->rowCount() == 0){
            throw new NotFoundException("Image with ID ".$imageId." does not exist.");
        }
    }

    public function createImage(Image $image): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO Images(owner_id, creator_id, name, description, price, is_moderated, is_onsale, alt_text) 
            VALUES (:ownerId, :creatorId, :name, :description, :price, :isModerated, :isOnsale, :altText);"
        );

        $stmt->bindValue(":ownerId", $image->ownerId, PDO::PARAM_INT); 
        $stmt->bindValue(":creatorId", $image->creatorId, PDO::PARAM_INT); 
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
        $stmt = $this->connection->prepare(
            "UPDATE Images 
            SET owner_id = :userId, is_onsale = 0, price = NULL
            WHERE id = :imageId;"
        );

        $stmt->bindValue(":imageId", $imageId, PDO::PARAM_INT); 
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT); 

        $stmt->execute();

        if($stmt->rowCount() == 0){
            throw new NotFoundException("Image with ID ".$imageId." does not exist.");
        }
    }

    public function updateImageModerationByImageId(int $imageId, bool $isModerated)
    {
        $stmt = $this->connection->prepare(
            "UPDATE Images 
            SET is_moderated = :isModerated
            WHERE id = :imageId;"
        );

        $stmt->bindValue(":imageId", $imageId, PDO::PARAM_INT); 
        $stmt->bindValue(":isModerated", $isModerated, PDO::PARAM_BOOL); 

        $stmt->execute();

        if($stmt->rowCount() == 0){
            throw new NotFoundException("Image with ID ".$imageId." does not exist.");
        }
    }

    public function deleteImageByImageId(int $imageId)
    {
        $stmt = $this->connection->prepare("DELETE FROM Images WHERE id = :imageId");
        $stmt->bindValue(":imageId", $imageId, PDO::PARAM_INT); 
        $stmt->execute();

        if($stmt->rowCount() === 0){
            throw new NotFoundException("Image with ID ".$userId." does not exist.");
        }
    }
}