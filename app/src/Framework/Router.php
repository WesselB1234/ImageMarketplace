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
    private function getControllerNameSpaceOfDir($dir): ?string
    {
        $pos = strpos($dir, "/Controllers"); 
        
        if ($pos !== false) { 
            $namespaceWithExtension = substr($dir, $pos + 1); 
            $namespaceWithForwardSlashes = "App\\".pathinfo($namespaceWithExtension, PATHINFO_DIRNAME) . '\\' . pathinfo($namespaceWithExtension, PATHINFO_FILENAME);

            return str_replace("/", "\\", $namespaceWithForwardSlashes);
        }

        return null;
    }

    private function getDispatchDataFromRefController(ReflectionClass $refController, string $httpMethod, string $uri): ?RouterDispatchData
    {
        $foundRouteWithIncapableMethod = null;

        foreach ($refController->getMethods() as $refMethod) { 
            foreach ($refMethod->getAttributes() as $attribute) { 
                
                $route = $attribute->newInstance();

                if (!$route instanceof Route){
                    continue;
                }

                if (str_contains($uri, $route->getRoute())){

                    $allowedMethod = $route->getHttpMethod();

                    if ($allowedMethod === $httpMethod){
                        return new RouterDispatchData($route, $refMethod->getName());
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

    private function getParamsFromUriAndRoute(Route $route, string $uri): ?array
    {
        $uriParamsStr = str_replace($route->getRoute(), "", $uri);

        if (str_starts_with($uriParamsStr, "/")) {
            $uriParamsStr = substr($uriParamsStr, 1);
        }

        $uriParams = (empty($uriParamsStr) ? [] : explode("/", $uriParamsStr));
        $routeParams = $route->getParams(); 

        $uriParamsCount = count($uriParams);
        $routeParamsCount = ($routeParams === null ? 0 : count($routeParams));
        
        if ($uriParamsCount > $routeParamsCount || $uriParamsCount < $routeParamsCount){
            throw new NotFoundException("This route does not have the right amount of parameters.");
        }
        
        if ($routeParamsCount === 0){
            return null;
        }

        $params = [];

        foreach($routeParams as $i => $param){
            $params[$param] = $uriParams[$i];
        }

        return $params;
    }

    private function getDispatchDataFromDir(string $dir, string $httpMethod, string $uri): ?RouterDispatchData
    {
        $controllerNamespace = $this->getControllerNameSpaceOfDir($dir);

        if ($controllerNamespace === null || !class_exists($controllerNamespace)){
            throw new Exception("$controllerNamespace does not exist as a class.");
        }

        $refController = new ReflectionClass($controllerNamespace); 
        $dispatchData = $this->getDispatchDataFromRefController($refController, $httpMethod, $uri); 

        if ($dispatchData !== null){
            
            $dispatchData->setControllerNamespace($controllerNamespace); 
        
            return $dispatchData;
        }

        return null;
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
            else if ($fileExtension === "") {
                $dispatchData = $this->getDispatchDataRecursivelyThroughControllersFolder("$dir/$fileName", $httpMethod, $uri);   
            }
            
            if ($dispatchData !== null){
                return $dispatchData;
            }
        }

        return null;
    }

    private function callRouteMethod(RouterDispatchData $dispatchData, string $uri)
    {
        $methodName = $dispatchData->getMethodName();
        $route = $dispatchData->getRoute();
        $controllerNamespace = $dispatchData->getControllerNamespace();

        $params = $this->getParamsFromUriAndRoute($route, $uri);
                    
        $controller = new $controllerNamespace();
        $controller->$methodName($params);
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
