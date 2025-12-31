<?php

namespace App\Models\ViewModels;

use App\Models\Image;

class ImageSellingVM
{   
    public Image $image;
    public ?int $price;

    public function __construct(Image $image, ?int $price)
    {
        $this->image = $image;
        $this->price = $price;
    }
}