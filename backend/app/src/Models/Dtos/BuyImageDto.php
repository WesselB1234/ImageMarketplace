<?php

namespace App\Models\Dtos;

use JsonSerializable;

class BuyImageDto implements JsonSerializable
{
    private int $imageId;
    private int $ownerId;

    public function __construct(int $imageId, int $ownerId)
    {
        $this->imageId = $imageId;
        $this->ownerId = $ownerId;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function jsonSerialize(): array
    {
        return [
            "imageId" => $this->imageId,
            "ownerId" => $this->ownerId,
        ];
    }
}
