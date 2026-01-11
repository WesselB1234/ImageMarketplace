<?php

use function DI\autowire; 
use App\Services\UsersService;
use App\Services\ImagesService;
use App\Services\Interfaces\IUsersService;
use App\Services\Interfaces\IImagesService;

return [
    IImagesService::class => autowire(ImagesService::class),
];