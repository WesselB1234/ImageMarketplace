<?php

namespace App\Models;

use DateTime;

class Image
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

    public function setImageId(int $imageId): self
    {
        $this->imageId = $imageId;
        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function setOwnerId(?int $ownerId): self
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function setCreatorId(?int $creatorId): self
    {
        $this->creatorId = $creatorId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getIsModerated(): bool
    {
        return $this->isModerated;
    }

    public function setIsModerated(bool $isModerated): self
    {
        $this->isModerated = $isModerated;
        return $this;
    }

    public function getIsOnSale(): bool
    {
        return $this->isOnSale;
    }

    public function setIsOnSale(bool $isOnSale): self
    {
        $this->isOnSale = $isOnSale;
        return $this;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    public function setTimeCreated(DateTime $timeCreated): self
    {
        $this->timeCreated = $timeCreated;
        return $this;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): self
    {
        $this->altText = $altText;
        return $this;
    }
}