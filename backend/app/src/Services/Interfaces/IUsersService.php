<?php

namespace App\Services\Interfaces;

use App\Models\Dtos\UserDto;
use App\Models\Enums\UserRole;
use App\Models\User;

interface IUsersService
{
    public function getAllUsers(): array;
    public function getUserByUserId(int $userId): ?User;
    public function getUserByUserIdOrThrow(int $userId): User;
    public function updateUser(User $user);
    public function createUser(User $user): int;
    public function createUserDto(string $username, string $password, int $imageTokens, UserRole $role): UserDto;
    public function deleteUserByUserId(int $userId);
}