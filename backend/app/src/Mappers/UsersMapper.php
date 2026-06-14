<?php

namespace App\Mappers;

use App\Models\User;
use App\Models\Enums\UserRole;
use App\Models\Dtos\UserDto;

class UsersMapper
{
    public static function mapAssocUserToUserWithoutPassword(array $assoc): User
    {
        return User::constructKnownUserWithoutPassword(
            $assoc["user_id"],
            $assoc["username"],
            $assoc["image_tokens"],
            UserRole::from($assoc["role"])
        );
    }

    public static function mapAssocUserToUser(array $assoc): User
    {
        return User::constructFullyKnownUser(
            $assoc["user_id"],
            $assoc["username"],
            $assoc["password"],
            $assoc["image_tokens"],
            UserRole::from($assoc["role"])
        );
    }

    public static function mapUserToDto(User $user): UserDto
    {
        return new UserDto(
            $user->getUserId(),
            $user->getUsername(),
            $user->getImageTokens(),
            $user->getRole()
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
}
