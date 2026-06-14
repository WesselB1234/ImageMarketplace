<?php 

namespace App\Services;

use App\Mappers\UsersMapper;
use App\Models\Dtos\UserDto;
use App\Models\Enums\UserRole;
use App\Models\Exceptions\ConflictException;
use App\Models\Exceptions\ForbiddenException;
use App\Services\Interfaces\IUsersService;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Models\Exceptions\NotFoundException;
use App\Utils\PasswordHasherUtil;

class UsersService implements IUsersService
{
    private IUsersRepository $usersRepository; 
    private PasswordHasherUtil $passwordHasherUtil;

    public function __construct(IUsersRepository $usersRepository, PasswordHasherUtil $passwordHasherUtil){
        $this->usersRepository = $usersRepository;
        $this->passwordHasherUtil = $passwordHasherUtil;
    }

    public function getAllUsers(): array
    {
        $users = $this->usersRepository->getAllUsers();
        
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
            
        $this->throwIfUserIsNotValid($user);

        $password = $user->getPassword();

        if ($password !== null){
            $user->setPassword($this->passwordHasherUtil->getHashedPassword($password));
        }

        $this->usersRepository->updateUser($user);

        return UsersMapper::mapUserToDto($user);
    }
 
    private function throwIfUserIsNotValid(User $user)
    {
        $duplicateUser = $this->usersRepository->getUserByUsername($user->getUsername());

        if ($duplicateUser !== null && ($user->getUserId() !== null && $duplicateUser->getUserId() === $user->getUserId()) === false)
        {
            throw new ConflictException("User with username ".$user->getUsername(). " already exists.");
        }
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
        $this->throwIfUserIsNotValid($user);

        return $this->usersRepository->createUser($user);
    }

    public function deleteUserByUserId(int $userId, ?User $loggedInUser = null)
    {
        if ($loggedInUser !== null && intval($userId) === $loggedInUser->getUserId()) {
            throw new ForbiddenException("You cannot delete yourself.");
        }

        $this->usersRepository->deleteUserByUserId($userId);
    }
}
