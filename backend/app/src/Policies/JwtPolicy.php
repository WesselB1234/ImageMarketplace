<?php 

namespace App\Policies;

use App\Exception\InvalidAuthTokenException;
use stdClass;

class JwtPolicy
{
    public function enforceJwtPolicy(stdClass $decodedAuthToken) 
    {
        $this->enforceIssetAuthTokenProperties($decodedAuthToken);
        $this->enforceAuthTokenDomain($decodedAuthToken);
    }

    private function enforceIssetAuthTokenProperties(stdClass $decodedAuthToken)
    {
        if (!isset($decodedAuthToken->iss) || !isset($decodedAuthToken->exp) || !isset($decodedAuthToken->data) || !isset($decodedAuthToken->data->userId)) {
            throw new InvalidAuthTokenException("Auth token is not valid.");
        }
    }

    private function enforceAuthTokenDomain(stdClass $decodedAuthToken) 
    {
        if ($decodedAuthToken->iss !== $_ENV["DOMAIN"]) {
            throw new InvalidAuthTokenException("Auth token domain is not equal to actual domain.");
        }
    }
}
