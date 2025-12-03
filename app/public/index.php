<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    
    // Authentication
    $r->addRoute('GET', '/login', ['App\Controllers\AuthenticationController', 'loginIndex']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthenticationController', 'processLogin']);
    $r->addRoute('GET', '/register', ['App\Controllers\AuthenticationController', 'registerIndex']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthenticationController', 'processRegister']);
    $r->addRoute('POST', '/logout', ['App\Controllers\AuthenticationController', 'logout']);

    // Images
    $r->addRoute('GET', '/images', ['App\Controllers\ImagesController', 'index']);
    $r->addRoute('GET', '/images/{id}', ['App\Controllers\ImagesController', 'details']);
    $r->addRoute('GET', '/images/buy', ['App\Controllers\ImagesController', 'buyIndex']);
    $r->addRoute('POST', '/images/buy', ['App\Controllers\ImagesController', 'processBuy']);
    $r->addRoute('GET', '/images/sell', ['App\Controllers\ImagesController', 'sellIndex']);
    $r->addRoute('POST', '/images/sell', ['App\Controllers\ImagesController', 'processSell']);
    $r->addRoute('GET', '/images/upload', ['App\Controllers\ImagesController', 'uploadIndex']);
    $r->addRoute('POST', '/images/upload', ['App\Controllers\ImagesController', 'processUpload']);
    $r->addRoute('POST', '/images/{id}/moderate', ['App\Controllers\ImagesController', 'moderateImage']);
    $r->addRoute('POST', '/images/{id}/unmoderate', ['App\Controllers\ImagesController', 'unModerateImage']);
    $r->addRoute('GET', '/images/{id}', ['App\Controllers\ImagesController', 'deleteImage']);

    // Portfolio
    $r->addRoute('GET', '/portfolio', ['App\Controllers\PortfolioController', 'index']);

    // Users (admin only)
    $r->addRoute('GET', '/users', ['App\Controllers\UsersController', 'index']);
    $r->addRoute('GET', '/users/create', ['App\Controllers\UsersController', 'createIndex']);
    $r->addRoute('POST', '/users/create', ['App\Controllers\UsersController', 'processCreate']);
    $r->addRoute('GET', '/users/{id}/update', ['App\Controllers\UsersController', 'updateIndex']);
    $r->addRoute('POST', '/users/{id}/update', ['App\Controllers\UsersController', 'processUpdate']);
    $r->addRoute('GET', '/users/{id}', ['App\Controllers\UsersController', 'delete']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {

    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:

        $controllerName = $routeInfo[1][0];
        $methodName = $routeInfo[1][1];
        $queryVars = $routeInfo[2];
        
        $controller = new $controllerName();
        $controller->$methodName($queryVars);

        break;
}
