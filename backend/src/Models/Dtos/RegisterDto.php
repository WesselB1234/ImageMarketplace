<?php

namespace App\Models\Dtos;

use JsonSerializable;

class RegisterDto implements JsonSerializable
{
    private UserDto $userDto;
    private string $jwt;

    public function __construct(UserDto $userDto, string $jwt) 
    {
        $this->userDto = $userDto;
        $this->jwt = $jwt;
    }

    public function jsonSerialize(): array 
    { 
        return [
            "user" => $this->userDto,
            "jwt" => $this->jwt
        ];
    }
}