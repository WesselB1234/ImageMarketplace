<?php

namespace App\Controllers;

use App\Repositories\GuestbookRepository;

class GuestbookController
{
   private $guestbookRepository;

    public function __construct() {
        $this->guestbookRepository = new GuestbookRepository();
    }

    public function getAll($vars)
    {
        $posts = $this->guestbookRepository->getAll();

        require(__DIR__ . '/../Views/guestbook.php');
    }

    public function addNewMessage($vars){
        $this->guestbookRepository->addNewMessage();

        $this->getAll($vars);
    }
}
