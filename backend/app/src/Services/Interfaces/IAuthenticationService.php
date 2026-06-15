<?php

namespace App\Services\Interfaces;

use App\Models\Dtos\LoginDto;
use App\Models\Dtos\RegisterDto;
use App\Models\Dtos\UserDto;
use App\Models\User;

interface IAuthenticationService
{
    public function getLoggedInUser(): User;
    public function getLoggedInUserByRoleAuthorization(array $roles): User;
    public function login(string $username, string $password): LoginDto;
    public function register(string $username, string $password): RegisterDto;
    public function getLoggedInUserDto(): UserDto;
}