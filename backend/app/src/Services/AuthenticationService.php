<?php 

namespace App\Services;

use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;
use Firebase\JWT\JWT;

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
            'iss' => $_ENV["DOMAIN"],
            'iat' => $now,
            'exp' => $expiration,
            'data' => [
                'id' => $user->getUserId(),
                'username' => $user->getUsername(),
                'role' => $user->getRole()->value,
            ]
        ];

        error_log($_ENV["JWT_SECRET_KEY"]);
        
        return JWT::encode($payload, $_ENV["JWT_SECRET_KEY"], self::JWT_ALGORITHM);
    }

    // public function getUserFromJwt(string $jwt): ?User
    // {
    //     try {
    //         $decoded = JWT::decode($token, new Key(Config::$secretKey, self::JWT_ALGORITHM));
    //     } catch (\Exception $e) {
    //         return null; // Invalid token
    //     }

    //     // Get user by ID from the decoded token
    //     if (isset($decoded->data->id)) {
    //         return $this->userRepository->getById($decoded->data->id);
    //     }      
    // }

    // public function validateToken(string $token): bool
    // {
    //     try {
    //         $decoded = JWT::decode($token, new Key(Config::$secretKey, self::JWT_ALGORITHM));
            
    //         // Validate required claims
    //         if (!isset($decoded->iss) || !isset($decoded->aud) || !isset($decoded->exp)) {
    //             return false;
    //         }
            
    //         // Validate issuer and audience match domain
    //         if ($decoded->iss !== Config::$domain || $decoded->aud !== Config::$domain) {
    //             return false;
    //         }
            
    //         return true;
    //     } catch (\Exception $e) {
    //         return false; // Invalid token
    //     }
    // }

    public function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }
}
