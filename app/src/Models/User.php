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

    public function __construct()
    {
        
    }

    public static function constructFullyKnownUser(int $userId ,string $username, string $password, int $imageTokens, string $stringRole): User
    {
        $user = new self();    

        $user->setUserId($userId);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setImageTokens($imageTokens);
        $user->setRole(UserRole::from($stringRole));
        
        return $user;
    }

    public static function constructUnknownUser(string $username, string $password, int $imageTokens, string $stringRole): User
    {
        $user = new self();

        $user->setUsername($username);
        $user->setPassword($password);
        $user->setImageTokens($imageTokens);
        $user->setRole(UserRole::from($stringRole));
        
        return $user;
    }

    public static function constructKnownUserWithoutPassword(int $userId, string $username, int $imageTokens, string $stringRole): User
    {
        $user = new self();

        $user->setUserId($userId);
        $user->setUsername($username);
        $user->setImageTokens($imageTokens);
        $user->setRole(UserRole::from($stringRole));
        
        return $user;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId)
    {
        $this->userId = $userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
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

    public function setRole(UserRole $role)
    {
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