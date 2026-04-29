<?php

use function DI\autowire;

use App\Services\UsersService;
use App\Services\ImagesService;
use App\Services\AuthenticationService;
use App\Services\Interfaces\IUsersService;
use App\Services\Interfaces\IImagesService;
use App\Services\Interfaces\IAuthenticationService;

use App\Repositories\ImagesRepository;
use App\Repositories\UsersRepository;
use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Interfaces\IImagesRepository;

return [
    IImagesService::class => autowire(ImagesService::class),
    IUsersService::class => autowire(UsersService::class),
    IAuthenticationService::class => autowire(AuthenticationService::class),

    IImagesRepository::class => autowire(ImagesRepository::class),
    IUsersRepository::class => autowire(UsersRepository::class),
];
