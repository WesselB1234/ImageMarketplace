<?php

namespace App\Models\Dtos;

use App\Models\Enums\UserRole;
use JsonSerializable;

class UserDto implements JsonSerializable
{
    private ?int $userId;
    private string $username;
    private int $imageTokens;
    private UserRole $role;

    public function __construct(int $userId, string $username, int $imageTokens, UserRole $role) 
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->imageTokens = $imageTokens;
        $this->role = $role;
    }

    public function jsonSerialize(): array 
    { 
        return [ 
            "userId" => $this->userId, 
            "username" => $this->username, 
            "imageTokens" => $this->imageTokens, 
            "role" => $this->role->value, 
        ]; 
    }
}