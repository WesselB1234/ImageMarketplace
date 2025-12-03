<?php

namespace App\Models;

class User
{
    public int $userId;
    public string $userName;
    public string $email;
    public string $password;
    public int $imageTokens;
}