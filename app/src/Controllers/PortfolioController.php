<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Interfaces\IImagesService;
use App\Services\ImagesService;

class PortfolioController extends Controller
{
    private IImagesService $imagesService;

    public function __construct()
    {
        $this->loggedInAuthorization();

        $this->imagesService = new ImagesService();
    }
    
    public function index()
    {
        $images = $this->imagesService->getAllImagesFromUserId($_SESSION["user"]->getUserId());

        $this->displayView(["viewModel" => $images], null);
    }
}
