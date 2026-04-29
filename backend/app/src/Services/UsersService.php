<?php 

namespace App\Services;

use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IUsersService;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use Exception;
use App\Models\Exceptions\NotFoundException;

class UsersService implements IUsersService
{
    private IUsersRepository $usersRepository; 
    private IAuthenticationService $authenticationService;

    public function __construct(IUsersRepository $usersRepository, IAuthenticationService $authenticationService){
        $this->usersRepository = $usersRepository;
        $this->authenticationService = $authenticationService;
    }

    public function getAllUsers(): array
    {
        return $this->usersRepository->getAllUsers();
    }

    public function getUserByUserId(int $userId): ?User
    {
        return $this->usersRepository->getUserByUserId($userId);
    }
    
    public function getUserByUserIdOrThrow(int $userId): User
    {
        $user = $this->getUserByUserId($userId);

        if ($user === null){
            throw new NotFoundException("User with ID ".$userId." does not exist.");
        }

        return $user;
    }

    public function updateUser(User $user)
    {
        $this->throwIfUserIsNotValid($user);

        $password = $user->getPassword();

        if ($password !== null){
            $user->setPassword($this->authenticationService->getHashedPassword($password));
        }

        $this->usersRepository->updateUser($user);
    }
 
    private function throwIfUserIsNotValid(User $user)
    {
        $duplicateUser = $this->usersRepository->getUserByUsername($user->getUsername());

        if ($duplicateUser !== null && ($user->getUserId() !== null && $duplicateUser->getUserId() === $user->getUserId()) === false)
        {
            throw new Exception("User with username ".$user->getUsername(). " already exists.");
        }
    }

    public function createUser(User $user): int
    {
        $this->throwIfUserIsNotValid($user);
        $user->setPassword($this->authenticationService->getHashedPassword($user->getPassword()));
        
        return $this->usersRepository->createUser($user);
    }

    public function deleteUserByUserId(int $userId)
    {
        $this->usersRepository->deleteUserByUserId($userId);
    }
}
