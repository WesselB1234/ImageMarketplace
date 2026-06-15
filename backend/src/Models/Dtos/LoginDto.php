<?php

namespace App\Models\Dtos;

use JsonSerializable;

class LoginDto implements JsonSerializable
{
    private string $jwt;

    public function __construct(string $jwt) 
    {
        $this->jwt = $jwt;
    }

    public function jsonSerialize(): array 
    { 
        return [ 
            "jwt" => $this->jwt, 
        ]; 
    }
}