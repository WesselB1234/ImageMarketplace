<?php 

namespace App\Services;

use App\Services\Interfaces\IUsersService;

class UsersService implements IUsersService
{
    public function getAllUsers(): array
    {
        return null;
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

    public function deleteUserByUserId(int $userId)
    {
        return null;
    }
}