<?php 

namespace App\Models\Helpers;

use App\Models\User;
use App\Models\Image;
use DateTime;

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

    public static function mapAssocImageToImage(array $assocImage): Image
    {
        return Image::constructFullyKnownImage(
            $assocImage["id"], 
            $assocImage["owner_id"], 
            $assocImage["creator_id"], 
            $assocImage["name"], 
            $assocImage["description"], 
            $assocImage["price"],
            $assocImage["is_moderated"],
            $assocImage["is_onsale"], 
            new DateTime($assocImage["time_created"]), 
            $assocImage["alt_text"]
        );
    }
}