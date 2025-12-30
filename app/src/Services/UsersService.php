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

    public function getUserByUsernameAndPassword(string $username, string $password): ?User
    {
        $user = $this->usersRepository->getFullyKnownUserByUsername($username);

        if (isset($user) && password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function updateUser(User $user)
    {
        $this->throwIfUserIsNotValid($user);
        $user->password = $this->getHashedPassword($user->password);
        $this->usersRepository->updateUser($user);
    }

    private function throwIfUserIsNotValid(User $user)
    {
        $duplicateUser = $this->usersRepository->getUserByUsername($user->username);

        if(isset($duplicateUser) && (isset($user->userId) && $duplicateUser->userId == $user->userId) == false)
        {
            throw new Exception("User with username ".$user->username. " already exists.");
        }

        if(!$this->getIsValidEmail($user->email))
        {
            throw new Exception("Email is not valid.");
        }
    }

    private function getIsValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }

    public function createUser(User $user)
    {
        $this->throwIfUserIsNotValid($user);
        $user->password = $this->getHashedPassword($user->password);
        $this->usersRepository->createUser($user);
    }

    public function deleteUserByUserId(int $userId)
    {
        $this->usersRepository->deleteUserByUserId($userId);
    }
}