<?php

namespace App\Models;

use App\Models\Enums\UserRole;

class User
{
    private ?int $userId;
    private string $username;
    private string $email;
    private ?string $password;
    private int $imageTokens;
    private UserRole $role;

    public function __construct()
    {
        
    }

    public static function constructFullyKnownUser(int $userId ,string $username, string $email, string $password, int $imageTokens, string $stringRole): User
    {
        $user = new self();    

        $user->setUserId($userId);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setImageTokens($imageTokens);
        $user->setRole(UserRole::from($stringRole));
        
        return $user;
    }

    public static function constructUnknownUser(string $username, string $email, string $password, int $imageTokens, string $stringRole): User
    {
        $user = new self();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setImageTokens($imageTokens);
        $user->setRole(UserRole::from($stringRole));
        
        return $user;
    }

    public static function constructKnownUserWithoutPassword(int $userId, string $username, string $email, int $imageTokens, string $stringRole): User
    {
        $user = new self();

        $user->setUserId($userId);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setImageTokens($imageTokens);
        $user->setRole(UserRole::from($stringRole));
        
        return $user;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getImageTokens(): int
    {
        return $this->imageTokens;
    }

    public function setImageTokens(int $imageTokens): self
    {
        $this->imageTokens = $imageTokens;
        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }
}