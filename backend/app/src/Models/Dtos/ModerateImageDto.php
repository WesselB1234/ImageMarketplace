<?php

namespace App\Models\Dtos;

use JsonSerializable;

class ModerateImageDto implements JsonSerializable
{
    private int $imageId;
    private bool $isModerated;

    public function __construct(int $imageId, bool $isModerated)
    {
        $this->imageId = $imageId;
        $this->isModerated = $isModerated;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getIsModerated(): bool
    {
        return $this->isModerated;
    }

    public function jsonSerialize(): array
    {
        return [
            "imageId" => $this->imageId,
            "isModerated" => $this->isModerated
        ];
    }
}
