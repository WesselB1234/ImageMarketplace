<?php

namespace App\Models\ApiResponses;

use JsonSerializable;

class ErrorDto implements JsonSerializable
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function jsonSerialize(): array
    {
        return [
            "message" => $this->message
        ];
    }
}
