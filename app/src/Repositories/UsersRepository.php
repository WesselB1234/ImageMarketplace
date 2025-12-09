<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Repository;
use App\Models\User;

class UsersRepository extends Repository implements IUsersRepository
{
    public function getAllUsers(): array
    {
        $sql = 'SELECT id, userName, email, image_tokens FROM Users';
        $result = $this->connection->query($sql);
        
        return $result->fetchAll(\PDO::FETCH_ASSOC);
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

    public function deleteUserByUserId(int $id)
    {
        return null;
    }
}