<?php

namespace App\Models\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    private string $httpMethod;
    private string $route;
    private ?array $params;

    public function __construct(string $httpMethod, string $route, ?array $params = null) 
    {
        $this->httpMethod = $httpMethod;
        $this->route = $route;
        $this->params = $params;
    }

    public function getHttpMethod(): string 
    {
        return $this->httpMethod;
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
