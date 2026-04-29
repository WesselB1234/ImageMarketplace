<?php

namespace App\Models\Dtos;

use App\Models\User;
use JsonSerializable;

class AuthorizationTestDto implements JsonSerializable
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function jsonSerialize(): array
    {
        return $this->user->jsonSerialize();
    }
}
