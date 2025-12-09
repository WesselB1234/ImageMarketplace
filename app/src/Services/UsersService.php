<?php 

namespace App\Services;

use App\Services\Interfaces\IUsersService;
use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\UsersRepository;
use App\Models\User;

class UsersService implements IUsersService
{
    private IUsersRepository $usersRepository; 

    public function __construct(){
        $this->usersRepository = new UsersRepository();
    }

    public function getAllUsers(): array
    {
        return $this->usersRepository->getAllUsers();
    }

    public function getUserByUserId(int $userId): ?User
    {
        return $this->usersRepository->getUserByUserId($userId);
    }

    public function updateUser(User $user)
    {
        $this->usersRepository->updateUser($user);
    }

    public function createUser(User $user)
    {
        $this->usersRepository->createUser($user);
    }

    public function deleteUserByUserId(int $userId)
    {
        $this->usersRepository->deleteUserByUserId($userId);
    }
}