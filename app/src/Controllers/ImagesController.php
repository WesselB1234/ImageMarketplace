<?php

namespace App\Controllers;

use App\Controllers\Controller;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->loggedInAuthorization();
    }

    public function index()
    {

    }

    public function details()
    {

    }

    public function buyIndex()
    {

    }

    public function processBuy()
    {

    }

    public function updateIndex()
    {

    }

    public function processUpdate()
    {

    }

    public function setOnSale()
    {

    }

    public function removeOnSale()
    {

    }

    public function uploadIndex()
    {

    }

    public function processUpload()
    {

    }

    public function moderateImage()
    {

    }

    public function unModerateImage()
    {
        $this->adminAuthorization();
    }

    public function deleteImage()
    {
        $this->adminAuthorization();
    }
}
