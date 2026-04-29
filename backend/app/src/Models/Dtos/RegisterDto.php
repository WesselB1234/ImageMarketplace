<?php

namespace App\Models\Dtos;

use App\Models\User;
use JsonSerializable;

class RegisterDto implements JsonSerializable
{
    private User $user;
    private string $jwt;

    public function __construct(User $user, string $jwt) 
    {
        $this->user = $user;
        $this->jwt = $jwt;
    }

    public function jsonSerialize(): array 
    { 
        return [
            "userId" => $this->user->getUserId(),
            "username" => $this->user->getUsername(),
            "image_tokens" => $this->user->getImageTokens(),
            "jwt" => $this->jwt,
        ];
    }
}