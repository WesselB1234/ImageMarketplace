<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUsersRepository
{
    public function getAllUsers(): array;
    public function getUserByUserId(int $userId): ?User;
    public function getUserByUsername(string $username): ?User;
    public function updateUser(User $user);
    public function createUser(User $user);
    public function updateTokensBalanceByUserId(int $userId, int $newTokensBalance);
    public function deleteUserByUserId(int $userId);
}