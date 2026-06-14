<?php

namespace App\Services\Interfaces;

use App\Models\Dtos\UserDto;
use App\Models\Enums\UserRole;
use App\Models\User;

interface IUsersService
{
    public function getAllUsers(?int $page, ?int $pageSize): array;
    public function getUserByUserId(int $userId): UserDto;
    public function updateUser(int $userId, string $username, ?string $password, int $imageTokens, UserRole $role): UserDto;
    public function createUser(User $user): int;
    public function createUserDto(string $username, string $password, int $imageTokens, UserRole $role): UserDto;
    public function deleteUserByUserId(int $userId, ?User $loggedInUser = null);
}