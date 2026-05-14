<?php 

namespace App\Services;

use App\Models\Enums\UserRole;
use App\Models\Exceptions\InvalidAuthTokenException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class AuthenticationService implements IAuthenticationService
{
    private IUsersRepository $usersRepository; 
    private const JWT_ALGORITHM = 'HS256';

    public function __construct(IUsersRepository $usersRepository){
        $this->usersRepository = $usersRepository;
    }
    
    public function getUserByUsernameAndPassword(string $username, string $password): ?User
    {
        $user = $this->usersRepository->getFullyKnownUserByUsername($username);

        if ($user !== null && password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    public function generateAuthTokenFromUser(User $user): string
    {
        $now = time();
        $expiration = $now + ($_ENV["TOKEN_EXPIRATION_IN_HOURS"] * 3600);

        $payload = [
            "iss" => $_ENV["DOMAIN"],
            "iat" => $now,
            "exp" => $expiration,
            "data" => $user
        ];
        
        return JWT::encode($payload, $_ENV["TOKEN_SECRET_KEY"], self::JWT_ALGORITHM);
    }

    public function getDecodedAuthToken(string $authToken): stdClass
    {
        return JWT::decode($authToken, new Key($_ENV["TOKEN_SECRET_KEY"], self::JWT_ALGORITHM));
    }

    public function validateAuthToken(stdClass $decodedAuthToken)
    {
        if (!isset($decodedAuthToken->iss) || !isset($decodedAuthToken->exp) || !isset($decodedAuthToken->data) || !isset($decodedAuthToken->data->userId)) {
            throw new NotAuthorizedException("Auth token is not valid.");
        }
        
        if ($decodedAuthToken->iss !== $_ENV["DOMAIN"]) {
            throw new NotAuthorizedException("Auth token domain is not equal to actual domain.");
        }
    }

    public function isUserEqualToDecodedAuthToken(User $user, stdClass $decodedAuthToken): bool
    {
        $data = $decodedAuthToken->data;

        if ($data->role !== $user->getRole()->value || $data->username !== $user->getUsername() || $data->imageTokens !== $user->getImageTokens()) {
            return false;
        }

        return true;
    }

    public function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }

    public function getLoggedInUser(): User
    { 
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])) {
            throw new NotAuthorizedException("Authorization header is required.");
        }

        $authHeader = $_SERVER["HTTP_AUTHORIZATION"];
        $headerParts = explode(" ", $authHeader);
        
        if (count($headerParts) !== 2 || strtolower($headerParts[0]) !== "bearer") {
            throw new NotAuthorizedException("Invalid authorization header format.");
        }

        $authToken = $headerParts[1];
        $decodedAuthToken = $this->getDecodedAuthToken($authToken);
        $this->validateAuthToken($decodedAuthToken);
        
        $user = $this->usersRepository->getUserByUserId($decodedAuthToken->data->userId);

        if ($user === null) {
            throw new InvalidAuthTokenException("User in your auth token does not exist.");
        }

        if ($this->isUserEqualToDecodedAuthToken($user, $decodedAuthToken) === false) {
            header("Authorization: Bearer ". $this->generateAuthTokenFromUser($user));
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
}
