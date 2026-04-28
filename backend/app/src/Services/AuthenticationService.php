<?php 

namespace App\Services;

use App\Models\Exceptions\NotAuthorizedException;
use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use Exception;
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

    public function generateJwtFromUser(User $user): string
    {
        $now = time();
        $expiration = $now + ($_ENV["JWT_EXPIRATION_IN_HOURS"] * 3600);
        
        $payload = [
            "iss" => $_ENV["DOMAIN"],
            "iat" => $now,
            "exp" => $expiration,
            "data" => [
                "id" => $user->getUserId(),
                "username" => $user->getUsername(),
                "role" => $user->getRole()->value,
            ]
        ];
        
        return JWT::encode($payload, $_ENV["JWT_SECRET_KEY"], self::JWT_ALGORITHM);
    }

    public function getDecodedToken(string $token): stdClass
    {
        $decoded = JWT::decode($token, new Key($_ENV["JWT_SECRET_KEY"], self::JWT_ALGORITHM));
        
        if (!isset($decoded->iss) || !isset($decoded->exp) || !isset($decoded->data) || !isset($decoded->data->id)) {
            throw new NotAuthorizedException("Token is not valid.");
        }
        
        if ($decoded->iss !== $_ENV["DOMAIN"]) {
            throw new NotAuthorizedException("Token domain is not equal to actual domain.");
        }

        return $decoded;
    }

    public function compareUserInDecodedToken(User $user, stdClass $decoded): bool
    {
        $data = $decoded->data;

        if ($data->username !== $user->getUsername() || $data->role !== $user->getRole()->value) {
            return false;
        }

        return true;
    }

    public function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }
}
