<?php

namespace App\Models;

use App\Models\Attributes\Route;

class RouterDispatchData
{
    private Route $route;
    private string $methodName;
    private ?string $controllerNamespace;

    public function __construct(Route $route, string $methodName)
    {
        $this->route = $route;
        $this->methodName = $methodName;
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

    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    public function setControllerNamespace(string $controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace;
    }
}
