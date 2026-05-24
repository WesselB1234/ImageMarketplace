<?php

namespace App\Models\Dtos;

use JsonSerializable;

class SellImageDto implements JsonSerializable
{
    private int $imageId;
    private int $price;

    public function __construct(int $imageId, bool $price)
    {
        $this->imageId = $imageId;
        $this->price = $price;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getPrice(): bool
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            "imageId" => $this->imageId,
            "price" => $this->price
        ];
    }
}
