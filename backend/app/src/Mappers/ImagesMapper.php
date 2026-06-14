<?php

namespace App\Mappers;

use App\Models\Image;
use App\Models\Dtos\ImageDto;
use DateTime;

class ImagesMapper
{
    public static function mapAssocImageToImage(array $assoc): Image
    {
        return Image::constructFullyKnownImage(
            $assoc["id"],
            $assoc["owner_id"],
            $assoc["creator_id"],
            $assoc["name"],
            $assoc["description"],
            $assoc["price"],
            $assoc["is_moderated"],
            $assoc["is_onsale"],
            new DateTime($assoc["time_created"]),
            $assoc["alt_text"]
        );
    }

    public static function mapImageToDto(Image $image): ImageDto
    {
        $dto = new ImageDto(
            $image->getImageId(),
            $image->getOwnerId(),
            $image->getCreatorId(),
            $image->getName(),
            $image->getDescription(),
            $image->getPrice(),
            $image->getIsModerated(),
            $image->getIsOnSale(),
            $image->getTimeCreated(),
            $image->getAltText()
        );

        if ($image->getOwner() !== null) {
            $dto->setOwner(UsersMapper::mapUserToDto($image->getOwner()));
        }

        if ($image->getCreator() !== null) {
            $dto->setCreator(UsersMapper::mapUserToDto($image->getCreator()));
        }

        return $dto;
    }

    public static function mapImagesArrayToDtoList(array $images): array
    {
        $dtoList = [];

        foreach ($images as $image) {
            $dtoList[] = self::mapImageToDto($image);
        }

        return $dtoList;
    }
}
