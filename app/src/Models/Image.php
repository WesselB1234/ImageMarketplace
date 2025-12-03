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
}