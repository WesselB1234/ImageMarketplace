<?php

namespace App\Framework;

use App\Models\Attributes\Route;
use App\Exception\MethodNotAllowedException;
use App\Exception\NotFoundException;
use App\Models\RouteControllerMethod;
use ReflectionClass;
use DI\Container;
use FastRoute\RouteCollector;
use FastRoute;
use function FastRoute\simpleDispatcher;

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
            throw new NotFoundException("$controllerClassPath does not exist as a class.");
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
    
    private function callRouteMethodOfController($methodName, $controllerClassPath, $requestParams)
    {                    
        $controller = $this->container->get($controllerClassPath);
        $controller->$methodName($requestParams);
    }

    private function getRouteControllerMethodOfHttpRequest(string $httpMethod, string $uri): RouteControllerMethod
    { 
        if (!file_exists($this::ROUTES_CACHE_DIR)) {
            throw new NotFoundException("Route caching file does not exist.");
        }

        $cacheRoutes = require $this::ROUTES_CACHE_DIR;

        if (!isset($cacheRoutes[$httpMethod])){
            throw new NotFoundException("Cannot find specified http method.");
        }

        $dispatcher = simpleDispatcher(function (RouteCollector $r) use ($cacheRoutes) {
            foreach ($cacheRoutes as $cacheHttpMethod => $cacheMethodRoutes) {
                foreach ($cacheMethodRoutes as $cacheRoute => $controllerMethods) {
                    $r->addRoute($cacheHttpMethod, $cacheRoute, $controllerMethods);
                }
            }
        });

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException("HttpMethod is not allowed.");

            case FastRoute\Dispatcher::FOUND:
                
                $methodName = $routeInfo[1]["method_name"];
                $controllerPath = $routeInfo[1]["controller_path"];
                $requestParams = $routeInfo[2];

                return new RouteControllerMethod($methodName, $controllerPath, $requestParams);
        }

        throw new NotFoundException("Cannot find specified route.");;
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
        $routeControllerMethod = null;

        try{
            $routeControllerMethod = $this->getRouteControllerMethodOfHttpRequest($httpMethod, $uri);
        }
        catch(NotFoundException $e){
            $this->refreshRoutesCacheFile();
            $routeControllerMethod = $this->getRouteControllerMethodOfHttpRequest($httpMethod, $uri);
        }

        $this->callRouteMethodOfController($routeControllerMethod->getMethodName(), $routeControllerMethod->getControllerPath(), $routeControllerMethod->getRequestParams());
    }
}
