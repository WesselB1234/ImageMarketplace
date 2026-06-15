<?php 

namespace App\Utils;

class PasswordHasherUtil
{
    public function getHashedPassword($rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);   
    }
}
