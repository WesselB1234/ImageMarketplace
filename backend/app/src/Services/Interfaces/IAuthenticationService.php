<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface IAuthenticationService
{
    public function getUserByUsernameAndPassword(string $username, string $password): ?User;
    public function generateJwtFromUser(User $user): string;
    //public function getUserFromJwt(string $jwt): ?User;
    public function getHashedPassword($rawPassword): string;
}