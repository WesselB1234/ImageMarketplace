<?php

namespace App\Models\ApiResponses;

class ErrorResponse
{   
    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}