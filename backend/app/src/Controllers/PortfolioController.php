<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IImagesService;
use App\Models\Attributes\Route;
use App\Services\Interfaces\IUsersService;

class PortfolioController extends Controller
{
    private IImagesService $imagesService;

    public function __construct(IImagesService $imagesService, IUsersService $usersService)
    {
        parent::__construct($usersService);
        $this->loggedInAuthorization();

        $this->imagesService = $imagesService;
    }
    
    #[Route("GET", "/")] 
    #[Route("GET", "/portfolio")]
    public function index()
    {
        $images = $this->imagesService->getAllImagesFromUserId($this->loggedInUser->getUserId());

        $this->displayView(["viewModel" => $images]);
    }
}
