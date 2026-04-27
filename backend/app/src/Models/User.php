<?php

namespace App\Models;

use App\Models\Enums\UserRole;
use JsonSerializable;

class User implements JsonSerializable
{
    private ?int $userId;
    private string $username;
    private ?string $password;
    private int $imageTokens;
    private UserRole $role;

    private function __construct(?int $userId, string $username, ?string $password, int $imageTokens, UserRole $role) 
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
        $this->imageTokens = $imageTokens;
        $this->role = $role;
    }

    public static function constructUnknownUser(string $username, string $password, int $imageTokens, UserRole $role): User
    {
        return new self(
            null,
            $username,
            $password,
            $imageTokens,
            $role
        );
    }

    public static function constructKnownUserWithoutPassword(int $userId, string $username, int $imageTokens, UserRole $role): User
    {
        return new self(
            $userId,
            $username,
            null,
            $imageTokens,
            $role
        );
    }

    public static function constructFullyKnownUser(?int $userId, string $username, string $password, int $imageTokens, UserRole $role): User
    {
        return new self(
            $userId,
            $username,
            $password,
            $imageTokens,
            $role
        );
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

     public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    public function getImageTokens(): int
    {
        return $this->imageTokens;
    }

    public function setImageTokens(int $imageTokens)
    {
        $this->imageTokens = $imageTokens;
    }

    public function getRole(): UserRole
    {
        return $this->role;
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