<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUsersRepository
{
    public function getAllUsers(?int $page, ?int $pageSize): array;
    public function getUserByUserId(int $userId): ?User;
    public function getUserByUsername(string $username): ?User;
    public function getFullyKnownUserByUsername(string $username): ?User;
    public function updateUser(User $user);
    public function createUser(User $user): int;
    public function incrementBalanceByUserId(int $userId, int $imageTokens);
    public function decrementBalanceByUserId(int $userId, int $imageTokens);
    public function deleteUserByUserId(int $userId);
}