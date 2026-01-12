<?php

namespace App\Models;

use DateTime;
use JsonSerializable;

class Image implements JsonSerializable
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

    private function __construct(?int $imageId, ?int $ownerId, ?int $creatorId, string $name, string $description, ?int $price, bool $isModerated, bool $isOnSale, ?DateTime $timeCreated, string $altText) 
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

    public static function constructUnknownImage(?int $ownerId, ?int $creatorId, string $name, string $description, string $altText): Image
    {
        return new self(
            null,
            $ownerId,
            $creatorId,
            $name,
            $description,
            null,
            false,
            false,
            null,
            $altText
        ); 
    }

    public static function constructFullyKnownImage(?int $imageId, ?int $ownerId, ?int $creatorId, string $name, string $description, ?int $price, bool $isModerated, bool $isOnSale, DateTime $timeCreated, string $altText): Image
    {
        return new self(
            $imageId,
            $ownerId,
            $creatorId,
            $name,
            $description,
            $price,
            $isModerated,
            $isOnSale,
            $timeCreated,
            $altText
        ); 
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
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