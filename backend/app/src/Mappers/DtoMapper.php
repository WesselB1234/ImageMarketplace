<?php 

namespace App\Mappers;

use App\Models\Dtos\ImageDto;
use App\Models\Dtos\UserDto;
use App\Models\User;
use App\Models\Image;
use App\Models\Enums\UserRole;
use DateTime;

class DtoMapper
{
    public static function mapUserToDto(User $user): UserDto
    {
        return new UserDto(
            $user->getUserId(),
            $user->getUsername(),
            $user->getImageTokens(),
            $user->getRole()
        );
    }

    public static function mapImageToDto(Image $image): ImageDto
    {
        return new ImageDto(
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
    }

    public static function mapUsersArrayToDtoList(array $users): array
    {
        $dtoList = [];

        foreach ($users as $user) {
            $dtoList[] = self::mapUserToDto($user);
        }

        return $dtoList;
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
