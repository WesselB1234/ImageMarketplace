<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'home']);
    $r->addRoute('GET', '/hello/{name}', ['App\Controllers\HelloController', 'greet']);
    $r->addRoute('GET', '/guestbook', ['App\Controllers\GuestbookController', 'getAll']);
    $r->addRoute('POST', '/guestbook', ['App\Controllers\GuestbookController', 'addNewMessage']);
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
