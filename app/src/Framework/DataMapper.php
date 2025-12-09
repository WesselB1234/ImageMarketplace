<?php 

namespace App\Framework;

use App\Models\User;

class DataMapper{

    public static function mapAssocUserToUser(array $assocUser) : User
    {
        return User::constructKnownUserWithoutPassword(
            $assocUser["user_id"], 
            $assocUser["username"], 
            $assocUser["email"], 
            $assocUser["image_tokens"],
            $assocUser["role"]
        );
    }
}