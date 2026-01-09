<?php

namespace App\Models;

use DateTime;
use JsonSerializable;

class Image implements JsonSerializable
{
    private int $imageId;
    private ?int $ownerId;
    private ?int $creatorId;
    private string $name;
    private string $description;
    private ?int $price;
    private bool $isModerated;
    private bool $isOnSale;
    private ?DateTime $timeCreated;
    private string $altText;

    public function __construct()
    {
        
    }

    public static function constructUnknownImage(?int $ownerId, ?int $creatorId, string $name, string $description, string $altText): Image
    {
        $image = new self(); 
        
        $image->setOwnerId($ownerId); 
        $image->setCreatorId($creatorId); 
        $image->setName($name); 
        $image->setDescription($description); 
        $image->setAltText($altText); 

        $image->setIsModerated(false); 
        $image->setIsOnSale(false); 
 
        return $image;
    }

    public static function constructFullyKnownImage(int $imageId, ?int $ownerId, ?int $creatorId, string $name, string $description, ?int $price, bool $isModerated, bool $isOnSale, DateTime $timeCreated, string $altText): Image
    {
        $image = new self();

        $image->setImageId($imageId); 
        $image->setOwnerId($ownerId); 
        $image->setCreatorId($creatorId); 
        $image->setName($name); 
        $image->setDescription($description); 
        $image->setPrice($price); 
        $image->setIsModerated($isModerated); 
        $image->setIsOnSale($isOnSale); 
        $image->setTimeCreated($timeCreated); 
        $image->setAltText($altText);
        
        return $image;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function setImageId(int $imageId)
    {
        $this->imageId = $imageId;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function setOwnerId(?int $ownerId)
    {
        $this->ownerId = $ownerId;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function setCreatorId(?int $creatorId)
    {
        $this->creatorId = $creatorId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price)
    {
        $this->price = $price;
    }

    public function getIsModerated(): bool
    {
        return $this->isModerated;
    }

    public function setIsModerated(bool $isModerated)
    {
        $this->isModerated = $isModerated;
    }

    public function getIsOnSale(): bool
    {
        return $this->isOnSale;
    }

    public function setIsOnSale(bool $isOnSale)
    {
        $this->isOnSale = $isOnSale;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    public function setTimeCreated(DateTime $timeCreated)
    {
        $this->timeCreated = $timeCreated;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function setAltText(string $altText)
    {
        $this->altText = $altText;
    }

    public function jsonSerialize(): array 
    { 
        return [ 
            "imageId" => $this->imageId, 
            "ownerId" => $this->ownerId, 
            "creatorId" => $this->creatorId, 
            "name" => $this->name, 
            "description" => $this->description, 
            "price" => $this->price, 
            "isModerated" => $this->isModerated, 
            "isOnSale" => $this->isOnSale, 
            "timeCreated" => $this->timeCreated?->format(DateTime::ATOM), 
            "altText" => $this->altText 
        ]; 
    }
}