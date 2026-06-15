<?php

namespace App\Models;

use DateTime;

class Image
{
    private ?int $imageId;
    private ?int $ownerId;
    private ?User $owner;
    private ?int $creatorId;
    private ?User $creator;
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

    public function getImageId(): ?int
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

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    public function setCreator(User $creator)
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

    public function setTimeCreated(DateTime $timeCreated)
    {
        $this->timeCreated = $timeCreated;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }
}