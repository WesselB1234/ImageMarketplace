<?php

$loader = require __DIR__ . "/../vendor/autoload.php";

//use FastRoute\RouteCollector;
//use FastRoute\Dispatcher;
//use function FastRoute\simpleDispatcher;
use Dotenv\Dotenv;
use App\Models\Attributes\Route;

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

function getMethodNameAndRouteFromRefClassEqualToHttp(ReflectionClass $refClass): ?array
{
    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    foreach ($refClass->getMethods() as $refMethod) { 
        foreach ($refMethod->getAttributes() as $attribute) { 
            
            $attributeObj = $attribute->newInstance();

            if (!$attributeObj instanceof Route){
                continue;
            }

            if ($attributeObj->getRoute() === $uri){
                return [
                    "routeObj" => $attributeObj,
                    "methodName" => $refMethod->getName()
                ];
            }
        }  
    }

    return null;
}

function callRouteMethodIfPresentInController(string $dir)
{
    $controllerNamespace = getControllerNameSpaceOfDir($dir);

    if (!class_exists($controllerNamespace)){
        throw new Exception("$controllerNamespace does not exist as a class.");
    }

    $refClass = new ReflectionClass($controllerNamespace); 
    $methodNameAndRouteObj = getMethodNameAndRouteFromRefClassEqualToHttp($refClass); 

    if ($methodNameAndRouteObj !== null){

        $methodName = $methodNameAndRouteObj["methodName"];

        $controller = new $controllerNamespace();
        $controller->$methodName();
    }
}

function loopThroughControllersFolder(string $dir)
{
    $files = array_diff(scandir($dir), [".", ".."]);

    foreach ($files as $fileName){

        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExtension === "php"){
            callRouteMethodIfPresentInController("$dir/$fileName");
        }
        else if ($fileExtension === "") {
            loopThroughControllersFolder("$dir/$fileName");
        }
    }    
}

function init()
{
    loopThroughControllersFolder(__DIR__."/../src/Controllers");
}

init();