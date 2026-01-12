<?php

namespace App\Models\ApiResponses;

use JsonSerializable;

class UserDeletionResponse implements JsonSerializable
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function jsonSerialize(): array
    {
        return [
            "userId" => $this->userId
        ];
    }
}
