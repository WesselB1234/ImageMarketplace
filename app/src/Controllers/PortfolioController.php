<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;
use App\Models\Attributes\Route;

class PortfolioController extends Controller
{
    private IImagesService $imagesService;

    public function __construct()
    {
        $this->loggedInAuthorization();

        $this->imagesService = new ImagesService();
    }
    
    #[Route("GET", "/")] 
    #[Route("GET", "/portfolio")]
    public function index()
    {
        $images = $this->imagesService->getAllImagesFromUserId($_SESSION["user"]->getUserId());

        $this->displayView(["viewModel" => $images]);
    }
}
