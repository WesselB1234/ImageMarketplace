<?php

namespace App\Models\Dtos;

use DateTime;
use JsonSerializable;

class ImageDto implements JsonSerializable
{
    private ?int $imageId;
    private ?int $ownerId;
    private ?int $creatorId;
    private string $name;
    private string $description;
    private ?int $price;
    private bool $isModerated;
    private bool $isOnSale;
    private ?DateTime $timeCreated;
    private string $altText;

    public function __construct(?int $imageId, ?int $ownerId, ?int $creatorId, string $name, string $description, ?int $price, bool $isModerated, bool $isOnSale, ?DateTime $timeCreated, string $altText) 
    {
        $this->imageId = $imageId;
        $this->ownerId = $ownerId;
        $this->creatorId = $creatorId;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->isModerated = $isModerated;
        $this->isOnSale = $isOnSale;
        $this->timeCreated = $timeCreated;
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