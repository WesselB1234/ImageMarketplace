<?php

namespace App\Models\Attributes;

use Attribute;

#[Attribute]
class Route
{
    private string $method;
    private ?array $params;

    public function __construct(string $method, ?array $params) 
    {
        $this->method = $method;
        $this->params = $params;
    }

    public function method(): string 
    {
        return $this->method;
    }

    public function params(): ?array 
    {
        return $this->params;
    }
}
