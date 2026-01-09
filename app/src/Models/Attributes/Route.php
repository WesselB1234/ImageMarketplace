<?php

namespace App\Models\Attributes;

use Attribute;

#[Attribute]
class Route
{
    private string $method;
    private string $route;
    private ?array $params;

    public function __construct(string $method, string $route, ?array $params) 
    {
        $this->method = $method;
        $this->route = $route;
        $this->params = $params;
    }

    public function getMethod(): string 
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getParams(): ?array 
    {
        return $this->params;
    }
}
