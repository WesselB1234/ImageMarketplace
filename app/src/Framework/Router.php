<?php

namespace App\Framework;

use App\Models\Attributes\Route;
use App\Models\Exceptions\NotAllowedException;
use App\Models\Exceptions\NotFoundException;
use App\Models\RouterDispatchData;
use Exception;
use ReflectionClass;
use DI\Container;

class Router
{
    private Container $container;

    public function __construct(Container $container){
        
        $this->container = $container;
    }

    // private function getControllerClassPathOfDir($dir): ?string
    // {
    //     $classPathPosition = strpos($dir, "/Controllers"); 
        
    //     if ($classPathPosition !== false) { 
    //         $classPathWithExtension = substr($dir, $classPathPosition + 1); 
    //         $classPathWithForwardSlashes = "App\\".pathinfo($classPathWithExtension, PATHINFO_DIRNAME) . "\\" . pathinfo($classPathWithExtension, PATHINFO_FILENAME);

    //         return str_replace("/", "\\", $classPathWithForwardSlashes);
    //     }

    //     return null;
    // }

    // private function getIsRouteMatch(array $routeSegments, ?array $routeParams, array $uriSegments): bool
    // { 
    //     $paramsCount = ($routeParams === null ? 0 : count($routeParams)); 
    //     $totalRouteSegmentCount = count($routeSegments) + $paramsCount;
    //     $totalUriSegmentCount = count($uriSegments);

    //     if ($totalRouteSegmentCount !== $totalUriSegmentCount){
    //         return false;
    //     }

    //     foreach ($routeSegments as $i => $routeSegment){
    //         if ($uriSegments[$i] !== $routeSegment){
    //             return false;
    //         }
    //     }

    //     return true;
    // }

    // private function getDispatchDataFromRefController(ReflectionClass $refController, string $httpMethod, string $uri): ?RouterDispatchData
    // {
    //     $foundRouteWithIncapableMethod = null;
    //     $uriSegments = explode("/", trim($uri, "/"));
        
    //     foreach ($refController->getMethods() as $refMethod) { 
    //         foreach ($refMethod->getAttributes() as $attribute) { 
                
    //             $route = $attribute->newInstance();

    //             if (!$route instanceof Route){
    //                 continue;
    //             }

    //             $routeSegments = explode("/", trim($route->getRoute(), "/"));
    //             $routeParams = $route->getParams();

    //             if ($this->getIsRouteMatch($routeSegments, $routeParams, $uriSegments)){

    //                 $allowedMethod = $route->getHttpMethod();

    //                 if ($allowedMethod === $httpMethod){

    //                     $requestParams = $this->getRequestParamsFromSegments($routeSegments, $routeParams, $uriSegments);
 
    //                     return new RouterDispatchData($refMethod->getName(), $refController->getName(), $requestParams);
    //                 }
    //                 else{
    //                     $foundRouteWithIncapableMethod = $route;
    //                 }
    //             }
    //         }  
    //     }

    //     if ($foundRouteWithIncapableMethod !== null){
    //         throw new NotAllowedException("This route can only be accessed by a ".$foundRouteWithIncapableMethod->getHttpMethod()." request.");
    //     }
        
    //     return null;
    // }

    // private function getRequestParamsFromSegments(array $routeSegments, ?array $routeParams, array $uriSegments): ?array
    // {
    //     if ($routeParams === null){
    //         return null;
    //     }

    //     $routeSegmentCount = count($routeSegments);
    //     $params = [];

    //     foreach($routeParams as $i => $param){
    //         $params[$param] = $uriSegments[$i + $routeSegmentCount];
    //     }

    //     return $params;
    // }

    // private function getDispatchDataFromDir(string $dir, string $httpMethod, string $uri): ?RouterDispatchData
    // {
    //     $controllerClassPath = $this->getControllerClassPathOfDir($dir);

    //     if ($controllerClassPath === null || !class_exists($controllerClassPath)){
    //         throw new Exception("$controllerClassPath does not exist as a class.");
    //     }

    //     $refController = new ReflectionClass($controllerClassPath); 
    //     return $this->getDispatchDataFromRefController($refController, $httpMethod, $uri); 
    // }

    // private function getDispatchDataRecursivelyThroughControllersFolder(string $dir, string $httpMethod, string $uri): ?RouterDispatchData
    // {
    //     $files = array_diff(scandir($dir), [".", ".."]);

    //     foreach ($files as $fileName){

    //         $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    //         $dispatchData = null;

    //         if ($fileExtension === "php"){
    //             $dispatchData = $this->getDispatchDataFromDir("$dir/$fileName", $httpMethod, $uri);
    //         }
    //         else if (empty($fileExtension)) {
    //             $dispatchData = $this->getDispatchDataRecursivelyThroughControllersFolder("$dir/$fileName", $httpMethod, $uri);   
    //         }
            
    //         if ($dispatchData !== null){
    //             return $dispatchData;
    //         }
    //     }

    //     return null;
    // }

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

    private function setCacheRoutesOfDir(string $dir, array &$cacheRoutes)
    {
        $controllerClassPath = $this->getControllerClassPathOfDir($dir);

        if ($controllerClassPath === null || !class_exists($controllerClassPath)){
            throw new Exception("$controllerClassPath does not exist as a class.");
        }

        $refController = new ReflectionClass($controllerClassPath); 
        
        foreach ($refController->getMethods() as $refMethod) { 
            foreach ($refMethod->getAttributes() as $attribute) { 
                
                $route = $attribute->newInstance();

                if (!$route instanceof Route){
                    continue;
                }

                $httpMethod = $route->getHttpMethod();

                if (!isset($cacheRoutes[$httpMethod])){
                    $cacheRoutes[$httpMethod] = [];
                }

                $cacheRoute = [
                    "controller_path" => $controllerClassPath,
                    "method_name" => $refMethod->getName()
                ];

                $routeRequestParams = $route->getRequestParams();

                if ($routeRequestParams !== null){
                    $cacheRoute["request_params"] = $routeRequestParams;
                }

                $cacheRoutes[$httpMethod][$route->getRoute()] = $cacheRoute;
            }  
        }
    }

    private function setCacheRecursivelyThroughControllersFolder(string $controllersDir, array &$cacheRoutes)
    {
        $files = array_diff(scandir($controllersDir), [".", ".."]);

        foreach ($files as $fileName){

            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            if ($fileExtension === "php"){
                $this->setCacheRoutesOfDir("$controllersDir/$fileName", $cacheRoutes);
            }
            else if (empty($fileExtension)){
                $this->setCacherecursivelyThroughControllersFolder("$controllersDir/$fileName", $cacheRoutes);   
            }
        }
    }

    private function callRouteMethod(RouterDispatchData $dispatchData)
    {
        $methodName = $dispatchData->getMethodName();
        $controllerClassPath = $dispatchData->getControllerClassPath();
        $requestParams = $dispatchData->getRequestParams();
                    
        $controller = $this->container->get($controllerClassPath);
        $controller->$methodName($requestParams);
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

    private function getDispatchDataFromRequest(string $httpMethod, string $uri): RouterDispatchData
    { 
        $cacheRoutes = require __DIR__."/../../Cache/routes.php";

        if (!isset($cacheRoutes[$httpMethod])){
            throw new Exception("bruh");
        }
        
        $cachedRoutesInHttpMethod = $cacheRoutes[$httpMethod];
        $uriSegments = explode("/", trim($uri, "/"));
        $uriMatcher = "";

        foreach($uriSegments as $uriSegment){
            
            $uriMatcher.="/$uriSegment";

            if (isset($cachedRoutesInHttpMethod[$uriMatcher])){
                
                $routeValues = $cachedRoutesInHttpMethod[$uriMatcher];
                $routeSegments = explode("/", trim($uriMatcher, "/"));
                $routeParams = (!isset($routeValues["request_params"]) ? null : $routeValues["request_params"]);

                $paramsCount = ($routeParams === null ? 0 : count($routeValues["request_params"])); 
                $totalRouteSegmentCount = count($routeSegments) + $paramsCount;
                $totalUriSegmentCount = count($uriSegments);

                if ($totalRouteSegmentCount !== $totalUriSegmentCount){
                    continue;
                }

                $requestParams = $this->getRequestParamsFromSegments($routeSegments, $routeParams, $uriSegments);

                return new RouterDispatchData($routeValues["method_name"], $routeValues["controller_path"], $requestParams);
            }
        }

        throw new Exception("bruh");
    }

    public function dispatch(string $httpMethod, string $uri)
    {
        $cacheRoutes = [];
        $this->setCacheRecursivelyThroughControllersFolder(__DIR__."/../Controllers", $cacheRoutes);
    
        file_put_contents(
            __DIR__."/../../Cache/routes.php",
            "<?php\n\n//THESE ROUTES ARE DYNAMICALLY GENERATED FROM ROUTER.PHP\n\nreturn " . var_export($cacheRoutes, true) . ";\n"
        );

        $dispatchData = $this->getDispatchDataFromRequest($httpMethod, $uri);

        $this->callRouteMethod($dispatchData);

        //require_once $filePath;

        

        // $dispatchData = $this->getDispatchDataRecursivelyThroughControllersFolder(__DIR__."/../Controllers", $httpMethod, $uri);

        // if ($dispatchData !== null){
        //     $this->callRouteMethod($dispatchData);
        // }
        // else{
        //     throw new NotFoundException("Cannot find specified route.");
        // }
    }
}
