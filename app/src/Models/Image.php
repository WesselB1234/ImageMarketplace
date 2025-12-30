<?php

namespace App\Models;

class Image
{
    public int $imageId;
    public int $ownerId;
    public string $name;
    public string $description;
    public ?int $price;
    public bool $isModerated;
    public bool $isOnSale;
    //public DateTime $timeCreated;
    public string $altText;

    public function __construct()
    {
        
    }

    public static function constructUnknownImage(int $ownerId, string $name, string $description, string $altText): Image
    {
        $image = new self();

        $image->ownerId = $ownerId;
        $image->name = $name;
        $image->description = $description;
        $image->altText = $altText;
        
        $image->isModerated = false;
        $image->isOnSale = false;
        
        return $image;
    }

    public static function constructFullyKnownImage(int $imageId, int $ownerId, string $name, string $description, ?int $price, bool $isModerated, bool $isOnSale, string $altText): Image//DateTime $timeCreated, string $altText): Image
    {
        $image = new self();

        $image->imageId = $imageId; 
        $image->ownerId = $ownerId; 
        $image->name = $name; 
        $image->description = $description; 
        $image->price = $price; 
        $image->isModerated = $isModerated; 
        $image->isOnSale = $isOnSale; 
        $image->altText = $altText;
        
        return $image;
    }
}