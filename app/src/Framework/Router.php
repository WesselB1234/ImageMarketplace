<?php

namespace App\Framework;

use App\Models\Attributes\Route;
use App\Models\Exceptions\NotAllowedException;
use App\Models\Exceptions\NotFoundException;
use App\Models\RouterDispatchData;
use Exception;
use ReflectionClass;

class Router
{
    private function getControllerClassPathOfDir($dir): ?string
    {
        $classPathPosition = strpos($dir, "/Controllers"); 
        
        if ($classPathPosition !== false) { 
            $classPathWithExtension = substr($dir, $classPathPosition + 1); 
            $classPathWithForwardSlashes = "App\\".pathinfo($classPathWithExtension, PATHINFO_DIRNAME) . "\\" . pathinfo($classPathWithExtension, PATHINFO_FILENAME);

            return str_replace("/", "\\", $classPathWithForwardSlashes);
        }

        return null;
    }

    function getIsRouteMatch(array $routeSegments, ?array $routeParams, array $uriSegments): bool
    { 
        $paramsCount = ($routeParams === null ? 0 : count($routeParams)); 
        $totalRouteSegmentCount = count($routeSegments) + $paramsCount;
        $totalUriSegmentCount = count($uriSegments);

        if ($totalRouteSegmentCount !== $totalUriSegmentCount){
            return false;
        }

        foreach ($routeSegments as $i => $routeSegment){
            if ($uriSegments[$i] !== $routeSegment){
                return false;
            }
        }

        return true;
    }

    private function getDispatchDataFromRefController(ReflectionClass $refController, string $httpMethod, string $uri): ?RouterDispatchData
    {
        $foundRouteWithIncapableMethod = null;
        $uriSegments = explode("/", trim($uri, "/"));
        
        foreach ($refController->getMethods() as $refMethod) { 
            foreach ($refMethod->getAttributes() as $attribute) { 
                
                $route = $attribute->newInstance();

                if (!$route instanceof Route){
                    continue;
                }

                $routeSegments = explode("/", trim($route->getRoute(), "/"));
                $routeParams = $route->getParams();

                if ($this->getIsRouteMatch($routeSegments, $routeParams, $uriSegments)){

                    $allowedMethod = $route->getHttpMethod();

                    if ($allowedMethod === $httpMethod){

                        $requestParams = $this->getRequestParamsFromSegments($routeSegments, $routeParams, $uriSegments);
 
                        return new RouterDispatchData($route, $refMethod->getName(), $refController->getName(), $requestParams);
                    }
                    else{
                        $foundRouteWithIncapableMethod = $route;
                    }
                }
            }  
        }

        if ($foundRouteWithIncapableMethod !== null){
            throw new NotAllowedException("This route can only be accessed by a ".$foundRouteWithIncapableMethod->getHttpMethod()." request.");
        }
        
        return null;
    }

    private function getRequestParamsFromSegments(array $routeSegments, ?array $routeParams, array $uriSegments): ?array
    {
        if ($routeParams === null){
            return null;
        }

        $routeSegmentCount = count($routeSegments);
        $params = [];

        foreach($routeParams as $i => $param){
            $params[$param] = $uriSegments[$i + $routeSegmentCount];
        }

        return $params;
    }

    private function getDispatchDataFromDir(string $dir, string $httpMethod, string $uri): ?RouterDispatchData
    {
        $controllerClassPath = $this->getControllerClassPathOfDir($dir);

        if ($controllerClassPath === null || !class_exists($controllerClassPath)){
            throw new Exception("$controllerClassPath does not exist as a class.");
        }

        $refController = new ReflectionClass($controllerClassPath); 
        return $this->getDispatchDataFromRefController($refController, $httpMethod, $uri); 
    }

    private function getDispatchDataRecursivelyThroughControllersFolder(string $dir, string $httpMethod, string $uri): ?RouterDispatchData
    {
        $files = array_diff(scandir($dir), [".", ".."]);

        foreach ($files as $fileName){

            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $dispatchData = null;

            if ($fileExtension === "php"){
                $dispatchData = $this->getDispatchDataFromDir("$dir/$fileName", $httpMethod, $uri);
            }
            else if (empty($fileExtension)) {
                $dispatchData = $this->getDispatchDataRecursivelyThroughControllersFolder("$dir/$fileName", $httpMethod, $uri);   
            }
            
            if ($dispatchData !== null){
                return $dispatchData;
            }
        }

        return null;
    }

    private function callRouteMethod(RouterDispatchData $dispatchData)
    {
        $methodName = $dispatchData->getMethodName();
        $route = $dispatchData->getRoute();
        $controllerClassPath = $dispatchData->getControllerClassPath();
        $requestParams = $dispatchData->getRequestParams();
                    
        $controller = new $controllerClassPath();
        $controller->$methodName($requestParams);
    }

    public function dispatch(string $httpMethod, string $uri)
    {
        $methodNameAndRouteObj = $this->getDispatchDataRecursivelyThroughControllersFolder(__DIR__."/../Controllers", $httpMethod, $uri);

        if ($methodNameAndRouteObj !== null){
            $this->callRouteMethod($methodNameAndRouteObj, $uri);
        }
        else{
            throw new NotFoundException("Cannot find specified route.");
        }
    }
}
