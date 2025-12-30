<?php

namespace App\Models\ViewModels;

use App\Models\User;
use App\Models\Image;

class ImageDetailsVM
{   
    public ?User $ownerUser;
    public ?User $creatorUser;
    public Image $image;

    public function __construct(Image $image, ?User $ownerUser, ?User $creatorUser)
    {
        $this->image = $image;
        $this->ownerUser = $ownerUser;
        $this->creatorUser = $creatorUser;
    }
}