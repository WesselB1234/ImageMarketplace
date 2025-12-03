<?php

namespace App\Controllers;

use App\Controllers\Controller;

class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->loggedInAuthorization();
    }
    
    public function index()
    {
        // Read the image file into a string
        $imagePath = "../public/assets/images/Core1NoBackground.png";
        $imageData = file_get_contents($imagePath);

        // Encode the binary data as Base64
        $imageString = base64_encode($imageData);

        // Output or use the string
        echo "<img src='data:image/jpeg;base64,". $imageString . "' />";
    }
}
