<?php

namespace App\Models\ViewModels;

use App\Models\Image;

class ImageSellingVM
{
    private Image $image;
    private ?int $price;

    public function __construct(Image $image, ?int $price)
    {
        $this->image = $image;
        $this->price = $price;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price)
    {
        $this->price = $price;
    }
}
