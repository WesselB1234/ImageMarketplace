<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Repository;

class UsersRepository extends Repository implements IUsersRepository
{
    public function getAllUsers(): array
    {
        return [];
    }

    public function getUserByUserId(int $userId): ?User
    {
        return null;
    }

    public function updateUser(User $user)
    {
        return null;
    }

    public function createUser(User $user)
    {
        return null;    
    }

    public function updateTokensBalanceByUserId(int $userId, int $newTokensBalance)
    {
        return null;
    }

    public function deleteUserById(int $id)
    {
        return null;
    }
}