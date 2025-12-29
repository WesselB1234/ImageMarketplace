<?php 

namespace App\Models\Helpers;

use App\Models\User;

class DataMapper{

    public static function mapAssocUserToUser(array $assocUser): User
    {
        return User::constructKnownUserWithoutPassword(
            $assocUser["user_id"], 
            $assocUser["username"], 
            $assocUser["email"], 
            $assocUser["image_tokens"],
            $assocUser["role"]
        );
    }

    public static function mapAssocUserToFullyKnownUser(array $assocUser): User
    {
        return User::constructFullyKnownUser(
            $assocUser["user_id"], 
            $assocUser["username"], 
            $assocUser["email"], 
            $assocUser["password"], 
            $assocUser["image_tokens"],
            $assocUser["role"]
        );
    }
}