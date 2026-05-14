<?php

namespace App\Services\Interfaces;

use App\Models\Enums\UserRole;
use App\Models\User;
use stdClass;

interface IAuthenticationService
{
    public function getUserByUsernameAndPassword(string $username, string $password): ?User;
    public function generateAuthTokenFromUser(User $user): string;
    public function getDecodedAuthToken(string $authToken): stdClass;
    public function isUserEqualToDecodedAuthToken(User $user, stdClass $decodedAuthToken): bool;
    public function getHashedPassword($rawPassword): string;
    public function validateAuthToken(stdClass $decodedAuthToken);
    public function getLoggedInUser(): User;
    public function getLoggedInUserByRoleAuthorization(array $roles): User;
}