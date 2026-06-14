<?php 

namespace App\Services;

use App\Mappers\UsersMapper;
use App\Models\Dtos\UserDto;
use App\Models\Enums\UserRole;
use App\Policies\UsersPolicy;
use App\Services\Interfaces\IUsersService;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Exception\NotFoundException;
use App\Utils\PasswordHasherUtil;

class UsersService implements IUsersService
{
    private IUsersRepository $usersRepository; 
    private PasswordHasherUtil $passwordHasherUtil;
    private UsersPolicy $usersPolicy;

    public function __construct(IUsersRepository $usersRepository, PasswordHasherUtil $passwordHasherUtil, UsersPolicy $usersPolicy){
        $this->usersRepository = $usersRepository;
        $this->passwordHasherUtil = $passwordHasherUtil;
        $this->usersPolicy = $usersPolicy;
    }

    public function getAllUsers(?int $page, ?int $pageSize): array
    {
        $users = $this->usersRepository->getAllUsers($page, $pageSize);
        
        return UsersMapper::mapUsersArrayToDtoList($users);
    }

    public function getUserByUserId(int $userId): UserDto
    {
        $user = $this->getUserByUserIdOrThrow($userId);

        return UsersMapper::mapUserToDto($user);
    }
    
    private function getUserByUserIdOrThrow(int $userId): User
    {
        $user = $this->usersRepository->getUserByUserId($userId);

        if ($user === null){
            throw new NotFoundException("User with ID ".$userId." does not exist.");
        }

        return $user;
    }

    public function updateUser(int $userId, string $username, ?string $password, int $imageTokens, UserRole $role): UserDto
    {
        if (empty($password)) {
            $user = User::constructKnownUserWithoutPassword($userId, $username, $imageTokens, $role); 
        }
        else{
            $user = User::constructFullyKnownUser($userId, $username, $password, $imageTokens, $role);
        }
            
        $this->usersPolicy->enforceUserIsNotDuplicate($user);

        $password = $user->getPassword();

        if ($password !== null){
            $user->setPassword($this->passwordHasherUtil->getHashedPassword($password));
        }

        $this->usersRepository->updateUser($user);

        return UsersMapper::mapUserToDto($user);
    }

    public function createUserDto(string $username, string $password, int $imageTokens, UserRole $role): UserDto
    {
        $user = User::constructUnknownUser($username, $password, $imageTokens, $role); 
        
        $user->setPassword($this->passwordHasherUtil->getHashedPassword($user->getPassword()));
        $userId = $this->createUser($user);
        $user->setUserId($userId);
        
        return UsersMapper::mapUserToDto($user);
    }

    public function createUser(User $user): int
    {
        $this->usersPolicy->enforceUserIsNotDuplicate($user);

        return $this->usersRepository->createUser($user);
    }

    public function deleteUserByUserId(int $userId, ?User $loggedInUser = null)
    {
        $this->usersPolicy->enforceNotDeletingSelf($userId, $loggedInUser);
        $this->usersRepository->deleteUserByUserId($userId);
    }
}
