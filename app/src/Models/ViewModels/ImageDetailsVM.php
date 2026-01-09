<?php

namespace App\Models\ViewModels;

use App\Models\User;
use App\Models\Image;

class ImageDetailsVM
{
    private ?User $ownerUser;
    private ?User $creatorUser;
    private Image $image;

    public function __construct(Image $image, ?User $ownerUser, ?User $creatorUser)
    {
        $this->image = $image;
        $this->ownerUser = $ownerUser;
        $this->creatorUser = $creatorUser;
    }

    public function getOwnerUser(): ?User
    {
        return $this->ownerUser;
    }

    public function setOwnerUser(?User $ownerUser)
    {
        $this->ownerUser = $ownerUser;
    }

    public function getCreatorUser(): ?User
    {
        return $this->creatorUser;
    }

    public function setCreatorUser(?User $creatorUser)
    {
        $this->creatorUser = $creatorUser;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image)
    {
        $this->image = $image;
    }
}
