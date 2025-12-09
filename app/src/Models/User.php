<?php

namespace App\Models;

use App\Models\Enums\UserRole;

class User
{
    public int $userId;
    public string $username;
    public string $email;
    public string $password;
    public int $imageTokens;
    public UserRole $role;

    public function __construct(){

    }

    public static function constructUnknownUser(string $username, string $email, string $password, int $imageTokens, int $roleIndex) : User
    {
        $user = new self();

        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        $user->imageTokens = $imageTokens;
        $user->role = UserRole::from($roleIndex);
        
        return $user;
    }
}