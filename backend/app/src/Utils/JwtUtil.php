<?php 

namespace App\Utils;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class JwtUtil
{
    private const JWT_ALGORITHM = 'HS256';

    public function generateAuthTokenFromUser(User $user): string
    {
        $now = time();
        $expiration = $now + ($_ENV["TOKEN_EXPIRATION_IN_HOURS"] * 3600);

        $payload = [
            "iss" => $_ENV["DOMAIN"],
            "iat" => $now,
            "exp" => $expiration,
            "data" => [
                "userId" => $user->getUserId(),
                "username" => $user->getUsername(),
                "imageTokens" => $user->getImageTokens(),
                "role" => $user->getRole()->value
            ]
        ];
        
        return JWT::encode($payload, $_ENV["TOKEN_SECRET_KEY"], self::JWT_ALGORITHM);
    }

    public function getDecodedAuthToken(string $authToken): stdClass
    {
        return JWT::decode($authToken, new Key($_ENV["TOKEN_SECRET_KEY"], self::JWT_ALGORITHM));
    }

    public function isUserEqualToDecodedAuthToken(User $user, stdClass $decodedAuthToken): bool
    {
        $data = $decodedAuthToken->data;

        if ($data->role !== $user->getRole()->value || $data->username !== $user->getUsername() || $data->imageTokens !== $user->getImageTokens()) {
            return false;
        }

        return true;
    }
}
