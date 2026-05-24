<?php

namespace App\Models\Dtos;

use JsonSerializable;

class SellImageDto implements JsonSerializable
{
    private int $imageId;
    private ?int $price;
    private bool $isOnSale;

    public function __construct(int $imageId, ?int $price, bool $isOnSale)
    {
        $this->imageId = $imageId;
        $this->price = $price;
        $this->isOnSale = $isOnSale;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getIsOnSale(): bool
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            "imageId" => $this->imageId,
            "price" => $this->price,
            "isOnSale" => $this->isOnSale
        ];
    }
}
