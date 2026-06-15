<?php 

namespace App\Services;

use App\Mappers\UsersMapper;
use App\Models\Dtos\RegisterDto;
use App\Models\Dtos\LoginDto;
use App\Models\Dtos\UserDto;
use App\Models\Enums\UserRole;
use App\Exception\InvalidAuthTokenException;
use App\Exception\NotAuthorizedException;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use App\Services\Interfaces\IUsersService;
use App\Utils\JwtUtil;
use App\Policies\JwtPolicy;

class AuthenticationService implements IAuthenticationService
{
    private IUsersRepository $usersRepository; 
    private IUsersService $usersService;
    private JwtUtil $jwtUtil;
    private JwtPolicy $jwtPolicy;

    public function __construct(IUsersRepository $usersRepository, IUsersService $usersService, JwtUtil $jwtUtil, JwtPolicy $jwtPolicy){
        $this->usersRepository = $usersRepository;
        $this->usersService = $usersService;
        $this->jwtUtil = $jwtUtil;
        $this->jwtPolicy = $jwtPolicy;
    }
    
    private function getUserByUsernameAndPassword(string $username, string $password): ?User
    {
        $user = $this->usersRepository->getFullyKnownUserByUsername($username);

        if ($user !== null && password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    private function getAuthTokenFromHeader(): String 
    {
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])) {
            throw new NotAuthorizedException("Authorization header is required.");
        }

        $authHeader = $_SERVER["HTTP_AUTHORIZATION"];
        $headerParts = explode(" ", $authHeader);
        
        if (count($headerParts) !== 2 || strtolower($headerParts[0]) !== "bearer") {
            throw new NotAuthorizedException("Invalid authorization header format.");
        }

        return $headerParts[1];
    }

    public function getLoggedInUser(): User
    { 
        $authToken = $this->getAuthTokenFromHeader();
        $decodedAuthToken = $this->jwtUtil->getDecodedAuthToken($authToken);
        $this->jwtPolicy->enforceJwtPolicy($decodedAuthToken);
        
        $user = $this->usersRepository->getUserByUserId($decodedAuthToken->data->userId);

        if ($user === null) {
            throw new InvalidAuthTokenException("User in your auth token does not exist.");
        }

        if ($this->jwtUtil->isUserEqualToDecodedAuthToken($user, $decodedAuthToken) === false) {
            header("Authorization: Bearer ". $this->jwtUtil->generateAuthTokenFromUser($user));
        }

        return $user;
    }

    public function getLoggedInUserByRoleAuthorization(array $roles): User
    {
        $user = $this->getLoggedInUser();

        foreach ($roles as $role) {
            if ($user->getRole() === $role) {
                return $user;
            }
        }

        throw new NotAuthorizedException("User does not have the right role.");
    }

    public function login(string $username, string $password): LoginDto
    {            
        $user = $this->getUserByUsernameAndPassword($username, $password);
        
        if ($user == null){
            throw new NotAuthorizedException("Password or username is not correct.");
        }

        return new LoginDto($this->jwtUtil->generateAuthTokenFromUser($user));
    }

    public function register(string $username, string $password): RegisterDto
    {
        $user = User::constructUnknownUser($username, $password, 100, UserRole::User); 
        $userId = $this->usersService->createUser($user);
        $user->setUserId($userId);
        
        $userDto = UsersMapper::mapUserToDto($user);
        
        return new RegisterDto($userDto, $this->jwtUtil->generateAuthTokenFromUser($user));
    }

    public function getLoggedInUserDto(): UserDTO
    {
        $loggedInUser = $this->getLoggedInUser();
        
        return UsersMapper::mapUserToDto($loggedInUser);
    }
}
