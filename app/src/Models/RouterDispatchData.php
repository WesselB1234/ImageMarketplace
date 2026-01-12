<?php

namespace App\Models;

use App\Models\Attributes\Route;

class RouterDispatchData
{
    private string $methodName;
    private string $controllerClassPath;
    private ?array $requestParams;

    public function __construct(string $methodName, string $controllerClassPath, ?array $requestParams)
    {
        $this->methodName = $methodName;
        $this->controllerClassPath = $controllerClassPath;
        $this->requestParams = $requestParams;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getControllerClassPath(): string
    {
        return $this->controllerClassPath;
    }

    public function getRequestParams(): ?array
    {
        return $this->requestParams;
    }
}
