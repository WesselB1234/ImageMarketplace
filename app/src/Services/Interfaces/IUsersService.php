<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface IUsersService
{
    public function getAllUsers(): array;
    public function getUserByUserId(int $userId): ?User;
    public function getUserByUsernameAndPassword(string $username, string $password): ?User;
    public function updateUser(User $user);
    public function createUser(User $user);
    public function deleteUserByUserId(int $userId);
}