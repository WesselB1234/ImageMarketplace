<?php

namespace App\Models\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    private string $httpMethod;
    private string $route;
    private ?array $requestParams;

    public function __construct(string $httpMethod, string $route, ?array $requestParams = null) 
    {
        $this->httpMethod = $httpMethod;
        $this->route = $route;
        $this->requestParams = $requestParams;
    }

    public function getHttpMethod(): string 
    {
        return $this->httpMethod;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getRequestParams(): ?array 
    {
        return $this->requestParams;
    }
}
