<?php

namespace App\Models;

use App\Models\Attributes\Route;

class RouterDispatchData
{
    private Route $route;
    private string $methodName;
    private string $controllerClassPath;
    private ?array $requestParams;

    public function __construct(Route $route, string $methodName, string $controllerClassPath, ?array $requestParams)
    {
        $this->route = $route;
        $this->methodName = $methodName;
        $this->controllerClassPath = $controllerClassPath;
        $this->requestParams = $requestParams;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }
    
    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
    
    public function setMethodName(string $methodName)
    {
        $this->methodName = $methodName;
    }

    public function getControllerClassPath(): string
    {
        return $this->controllerClassPath;
    }

    public function setControllerClassPath(string $controllerClassPath)
    {
        $this->controllerClassPath = $controllerClassPath;
    }

    public function getRequestParams(): ?array
    {
        return $this->requestParams;
    }

    public function setRequestParams(?array $requestParams)
    {
        $this->requestParams = $requestParams;
    }
}
