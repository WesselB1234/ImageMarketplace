<?php

namespace App\Models;

class Image
{
    public int $imageId;
    public int $ownerId;
    public string $name;
    public string $description;
    public int $priceInTokens;
    public bool $isModerated;
    public bool $isOnSale;
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
}