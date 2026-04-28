<?php

namespace App\Services\Interfaces;

use App\Models\User;
use stdClass;

interface IAuthenticationService
{
    public function getUserByUsernameAndPassword(string $username, string $password): ?User;
    public function generateTokenFromUser(User $user): string;
    public function getDecodedToken(string $token): stdClass;
    public function compareUserInDecodedToken(User $user, stdClass $decoded): bool;
    public function getHashedPassword($rawPassword): string;
}