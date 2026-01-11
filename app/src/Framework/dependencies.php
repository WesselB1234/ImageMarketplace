<?php

use function DI\autowire; 
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;

return [
    IImagesService::class => autowire(ImagesService::class),
];