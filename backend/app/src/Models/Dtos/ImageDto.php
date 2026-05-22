<?php

namespace App\Models\Dtos;

use DateTime;
use JsonSerializable;

class ImageDto implements JsonSerializable
{
    private ?int $imageId;
    private ?int $ownerId;
    private ?UserDto $owner;
    private ?int $creatorId;
    private ?UserDto $creator;
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
        $this->owner = null;
        $this->creatorId = $creatorId;
        $this->creator = null;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->isModerated = $isModerated;
        $this->isOnSale = $isOnSale;
        $this->timeCreated = $timeCreated;
        $this->altText = $altText;
    }

    public function getImageId(): ?int
    {
        return $this->imageId;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function getOwner(): ?UserDto
    {
        return $this->owner;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function getCreator(): ?UserDto
    {
        return $this->creator;
    }

    public function setOwner(?UserDto $owner)
    {
        $this->owner = $owner;
    }

    public function setCreator(?UserDto $creator)
    {
        $this->creator = $creator;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getIsModerated(): bool
    {
        return $this->isModerated;
    }

    public function getIsOnSale(): bool
    {
        return $this->isOnSale;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function jsonSerialize(): array 
    { 
        return [ 
            "imageId" => $this->imageId, 
            "ownerId" => $this->ownerId, 
            "owner" => $this->owner,
            "creatorId" => $this->creatorId,
            "creator" => $this->creator,
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