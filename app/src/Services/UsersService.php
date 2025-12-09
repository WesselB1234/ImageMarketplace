<?php 

namespace App\Services;

use App\Services\Interfaces\IUsersService;
use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\UsersRepository;
use App\Models\User;
use Exception;

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

    private function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }

    public function createUser(User $user)
    {
        $duplicateUser = $this->usersRepository->getUserByUsername($user->username);

        if(isset($duplicateUser))
        {
            throw new Exception("User with username ".$user->username. " already exists.");
        }

        $user->password = $this->getHashedPassword($user->password);
        $this->usersRepository->createUser($user);
    }

    public function deleteUserByUserId(int $userId)
    {
        $this->usersRepository->deleteUserByUserId($userId);
    }
}