<?php

namespace App\Framework;

use App\Models\Attributes\Route;
use App\Models\Exceptions\NotFoundException;
use Exception;
use ReflectionClass;
use DI\Container;

class Router
{
    private Container $container;
    private const CACHE_FOLDER_DIR = __DIR__."/../../cache";
    private const ROUTES_CACHE_DIR = self::CACHE_FOLDER_DIR."/routes.php";
    private const CONTROLLERS_DIR = __DIR__."/../Controllers";

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

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

    private function setCacheRoutesRecursivelyThroughControllersFolder(string $controllersDir, array &$cacheRoutes)
    {
        $files = array_diff(scandir($controllersDir), [".", ".."]);

        foreach ($files as $fileName){

            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            if ($fileExtension === "php"){
                $this->setCacheRoutesOfDir("$controllersDir/$fileName", $cacheRoutes);
            }
            else if (empty($fileExtension)){
                $this->setCacheRoutesRecursivelyThroughControllersFolder("$controllersDir/$fileName", $cacheRoutes);   
            }
        }
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
    
    private function callRouteMethodOfController($methodName, $controllerClassPath, $requestParams)
    {                    
        $controller = $this->container->get($controllerClassPath);
        $controller->$methodName($requestParams);
    }

    private function callRouteOfHttpRequest(string $httpMethod, string $uri)
    { 
        if (!file_exists($this::ROUTES_CACHE_DIR)) {
            throw new NotFoundException("Route caching file does not exist.");
        }

        $cacheRoutes = require $this::ROUTES_CACHE_DIR;

        if (!isset($cacheRoutes[$httpMethod])){
            throw new NotFoundException("Cannot find specified http method.");
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

                $this->callRouteMethodOfController($routeValues["method_name"], $routeValues["controller_path"], $requestParams);
            }
        }

        throw new NotFoundException("Cannot find specified route.");
    }

    private function refreshRoutesCacheFile()
    {
        $cacheRoutes = [];
        
        $this->setCacheRoutesRecursivelyThroughControllersFolder($this::CONTROLLERS_DIR, $cacheRoutes);
    
        if (!is_dir($this::CACHE_FOLDER_DIR)){
            mkdir($this::CACHE_FOLDER_DIR);
        }

        file_put_contents(
            $this::ROUTES_CACHE_DIR,
            "<?php\n\n//THESE ROUTES ARE DYNAMICALLY GENERATED FROM ROUTER.PHP\n\nreturn " . var_export($cacheRoutes, true) . ";\n"
        );
    }

    public function dispatch(string $httpMethod, string $uri)
    {
        try{
            $this->callRouteOfHttpRequest($httpMethod, $uri);
        }
        catch(Exception $e){
            $this->refreshRoutesCacheFile();
            $this->callRouteOfHttpRequest($httpMethod, $uri);
        }
    }
}
