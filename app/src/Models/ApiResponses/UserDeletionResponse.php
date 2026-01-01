<?php

namespace App\Models\ApiResponses;

class UserDeletionResponse
{   
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}