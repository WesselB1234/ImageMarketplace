<?php

$loader = require __DIR__ . "/../vendor/autoload.php";

//use FastRoute\RouteCollector;
//use FastRoute\Dispatcher;
//use function FastRoute\simpleDispatcher;
use Dotenv\Dotenv;
use App\Models\Attributes\Route;
use App\Models\Exceptions\NotAllowedException;
use App\Models\Exceptions\NotFoundException;

session_start();

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

// $dispatcher = simpleDispatcher(function (RouteCollector $r) {

//     // Authentication
//     $r->addRoute('GET', '/login', ['App\Controllers\AuthenticationController', 'login']);
//     $r->addRoute('POST', '/login', ['App\Controllers\AuthenticationController', 'processLogin']);
//     $r->addRoute('GET', '/register', ['App\Controllers\AuthenticationController', 'register']);
//     $r->addRoute('POST', '/register', ['App\Controllers\AuthenticationController', 'processRegister']);
//     $r->addRoute('GET', '/logout', ['App\Controllers\AuthenticationController', 'logout']);

//     // Images
//     $r->addRoute('GET', '/images', ['App\Controllers\ImagesController', 'index']);
//     $r->addRoute('GET', '/images/details/{id}', ['App\Controllers\ImagesController', 'details']);
//     $r->addRoute('GET', '/images/sell/{id}', ['App\Controllers\ImagesController', 'sell']);
//     $r->addRoute('POST', '/images/sell/{id}', ['App\Controllers\ImagesController', 'processSell']);
//     $r->addRoute('GET', '/images/takeoffsale/{id}', ['App\Controllers\ImagesController', 'takeOffSale']);
//     $r->addRoute('GET', '/images/buy/{id}', ['App\Controllers\ImagesController', 'buyImage']);
//     $r->addRoute('GET', '/images/upload', ['App\Controllers\ImagesController', 'upload']);
//     $r->addRoute('POST', '/images/upload', ['App\Controllers\ImagesController', 'processUpload']);
//     $r->addRoute('GET', '/images/moderate/{id}/{isModerate}', ['App\Controllers\ImagesController', 'moderateImage']);
//     $r->addRoute('GET', '/images/delete/{id}', ['App\Controllers\ImagesController', 'deleteImage']);

//     // Portfolio
//     $r->addRoute('GET', '/', ['App\Controllers\PortfolioController', 'index']);
//     $r->addRoute('GET', '/portfolio', ['App\Controllers\PortfolioController', 'index']);

//     // Users (admin only)
//     $r->addRoute('GET', '/users', ['App\Controllers\UsersController', 'index']);
//     $r->addRoute('GET', '/users/create', ['App\Controllers\UsersController', 'create']);
//     $r->addRoute('POST', '/users/create', ['App\Controllers\UsersController', 'processCreate']);
//     $r->addRoute('GET', '/users/update/{id}', ['App\Controllers\UsersController', 'update']);
//     $r->addRoute('POST', '/users/update/{id}', ['App\Controllers\UsersController', 'processUpdate']);

//     // API endpoints
//     $r->addRoute('POST', '/users/api/delete', ['App\Controllers\ApiControllers\UsersApiController', 'delete']);
//     $r->addRoute('GET', '/users/api/getloggedinuser', ['App\Controllers\ApiControllers\UsersApiController', 'getLoggedInUser']);
//     $r->addRoute('GET', '/images/api/getonsaleimages', ['App\Controllers\ApiControllers\ImagesApiController', 'getOnSaleImages']);
// });

// $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// switch ($routeInfo[0]) {

//     case Dispatcher::NOT_FOUND:
//         http_response_code(404);
//         echo 'Not Found';
//         break;
//     case Dispatcher::METHOD_NOT_ALLOWED:
//         http_response_code(405);
//         echo 'Method Not Allowed';
//         break;
//     case Dispatcher::FOUND:

//         $controllerName = $routeInfo[1][0];
//         $methodName = $routeInfo[1][1];
//         $queryVars = $routeInfo[2];

//         $refClass = new ReflectionClass($controllerName); 
//         $refMethod = $refClass->getMethod($methodName);
//         $attributes = $refMethod->getAttributes();

//         foreach ($attributes as $attr) { 
//             $instance = $attr->newInstance(); // ← THIS creates your Route object 
//             var_dump($instance); 
//         }
        
//         $controller = new $controllerName();
//         $controller->$methodName($queryVars);

//         break;
// }

function getControllerNameSpaceOfDir($dir): ?string
{
    $pos = strpos($dir, "/Controllers"); 
    
    if ($pos !== false) { 
        $namespaceWithExtension = substr($dir, $pos + 1); 
        $namespaceWithForwardSlashes = "App\\".pathinfo($namespaceWithExtension, PATHINFO_DIRNAME) . '\\' . pathinfo($namespaceWithExtension, PATHINFO_FILENAME);

        return str_replace("/", "\\", $namespaceWithForwardSlashes);
    }

    return null;
}

function getMethodNameAndRouteFromRefController(ReflectionClass $refController): ?array
{
    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    foreach ($refController->getMethods() as $refMethod) { 
        foreach ($refMethod->getAttributes() as $attribute) { 
            
            $attributeObj = $attribute->newInstance();

            if (!$attributeObj instanceof Route){
                continue;
            }

            if (str_contains($uri, $attributeObj->getRoute())){

                $allowedMethod = $attributeObj->getHttpMethod();

                if ($allowedMethod === $httpMethod){
                    return [
                        "routeObj" => $attributeObj,
                        "methodName" => $refMethod->getName()
                    ];
                }
                else{
                    throw new NotAllowedException("This route can only be accessed by a $allowedMethod request.");
                }
            }
        }  
    }

    return null;
}

function getParamsFromUriAndRouteObj(Route $routeObj): ?array
{
    $uri = strtok($_SERVER["REQUEST_URI"], "?");
      
    $uriParamsStr = str_replace($routeObj->getRoute(), "", $uri);

    if (str_starts_with($uriParamsStr, "/")) {
        $uriParamsStr = substr($uriParamsStr, 1);
    }

    $uriParams = (empty($uriParamsStr) ? [] : explode("/", $uriParamsStr));
    $routeParams = $routeObj->getParams(); 

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

function getMethodNameAndRouteObjFromDir(string $dir): ?array
{
    $controllerNamespace = getControllerNameSpaceOfDir($dir);

    if (!class_exists($controllerNamespace)){
        throw new Exception("$controllerNamespace does not exist as a class.");
    }

    $refController = new ReflectionClass($controllerNamespace); 
    $methodNameAndRouteObj = getMethodNameAndRouteFromRefController($refController); 

    if ($methodNameAndRouteObj !== null){
        
        $methodNameAndRouteObj["controllerNamespace"] = $controllerNamespace; 
    
        return $methodNameAndRouteObj;
    }

    return null;
}

function callRouteMethod(array $methodNameAndRouteObj)
{
    $methodName = $methodNameAndRouteObj["methodName"];
    $routeObj = $methodNameAndRouteObj["routeObj"];
    $controllerNamespace = $methodNameAndRouteObj["controllerNamespace"];

    $params = getParamsFromUriAndRouteObj($routeObj);
                
    $controller = new $controllerNamespace();
    $controller->$methodName($params);
}

function recursivelyIterateThroughControllersFolder(string $dir): ?array
{
    $files = array_diff(scandir($dir), [".", ".."]);

    foreach ($files as $fileName){

        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExtension === "php"){

            $methodNameAndRouteObj = getMethodNameAndRouteObjFromDir("$dir/$fileName");

            if ($methodNameAndRouteObj !== null){
                return $methodNameAndRouteObj;
            }
        }
        else if ($fileExtension === "") {
            
            $methodNameAndRouteObj = recursivelyIterateThroughControllersFolder("$dir/$fileName");

            if ($methodNameAndRouteObj !== null){
                return $methodNameAndRouteObj;
            }
        }
    }

    return null;
}

function dispatch()
{
    $methodNameAndRouteObj = recursivelyIterateThroughControllersFolder(__DIR__."/../src/Controllers");

    if ($methodNameAndRouteObj !== null){
        callRouteMethod($methodNameAndRouteObj);
    }
    else{
        throw new NotFoundException("Cannot find specified route.");
    }
}

function init()
{
    try{
        dispatch();
        // CLAAAASSSES
    }
    catch(NotAllowedException $e){
        http_response_code(405);
        echo $e->getMessage(); 
    }
    catch(NotFoundException $e){
        http_response_code(404);
        echo $e->getMessage(); 
    }
    catch(Exception $e){ 
        http_response_code(400);
        echo $e->getMessage(); 
    }
}

init();