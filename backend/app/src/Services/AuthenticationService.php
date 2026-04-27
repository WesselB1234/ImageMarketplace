<?php 

namespace App\Services;

use App\Repositories\Interfaces\IUsersRepository;
use App\Models\User;
use App\Services\Interfaces\IAuthenticationService;

class AuthenticationService implements IAuthenticationService
{
    private IUsersRepository $usersRepository; 

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
        $expiration = $now + (Config::$tokenExpirationHours * 3600); // Convert hours to seconds
        
        // This payload still needs an iss (issuer) and aud (audience set)
        // The payload also need a data property containing the user id, username and email
        $payload = [
            'iss' => Config::$domain, // TODO: Issuer (available in the Config class as the domain property)
            'iat' => $now, // Issued at time
            'nbf' => $now, // Not before
            'exp' => $expiration, // Expiration time (24 hours from now)
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ]
        ];
        
        // TODO: Encode and return the token using the secret key and the HS256 algorithm `self::JWT_ALGORITHM`
        return JWT::encode($payload, Config::$secretKey, self::JWT_ALGORITHM);
    }

    public function getUserFromJwt(string $jwt): ?User
    {
        try {
            $decoded = JWT::decode($token, new Key(Config::$secretKey, self::JWT_ALGORITHM));
        } catch (\Exception $e) {
            return null; // Invalid token
        }

        // Get user by ID from the decoded token
        if (isset($decoded->data->id)) {
            return $this->userRepository->getById($decoded->data->id);
        }      
    }

    public function validateToken(string $token): bool
    {
        try {
            $decoded = JWT::decode($token, new Key(Config::$secretKey, self::JWT_ALGORITHM));
            
            // Validate required claims
            if (!isset($decoded->iss) || !isset($decoded->aud) || !isset($decoded->exp)) {
                return false;
            }
            
            // Validate issuer and audience match domain
            if ($decoded->iss !== Config::$domain || $decoded->aud !== Config::$domain) {
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            return false; // Invalid token
        }
    }

    public function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }
}
