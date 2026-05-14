<?php

namespace App\Models;

class RouteControllerMethod
{
    private string $methodName;
    private string $controllerPath;
    private ?array $requestParams;

    public function __construct(string $methodName, string $controllerPath, ?array $requestParams)
    {
        $this->methodName = $methodName;
        $this->controllerPath = $controllerPath;
        $this->requestParams = $requestParams;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getControllerPath(): string
    {
        return $this->controllerPath;
    }

    public function getRequestParams(): ?array
    {
        return $this->requestParams;
    }
}
