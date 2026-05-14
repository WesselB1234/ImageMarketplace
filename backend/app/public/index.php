<?php

$loader = require __DIR__."/../vendor/autoload.php";

use App\Framework\Router;
use DI\ContainerBuilder;
use DI\CompiledContainer;

function start()
{
    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    require_once __DIR__ . "/../src/Exception/GlobalExceptionHandler.php";

    $globalExceptionHandler = new GlobalExceptionHandler();

    set_exception_handler([$globalExceptionHandler, "dispatch"]);

    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Expose-Headers: X-Auth-Error, Authorization");
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json");

    if ($httpMethod === "OPTIONS") {
        http_response_code(204);
        exit();
    }

    session_start();

    $_ENV = parse_ini_file(__DIR__."/../.env");

    $containerCacheFile = __DIR__."/../cache/CompiledContainer.php";

    if (file_exists($containerCacheFile)) {
        require_once $containerCacheFile;
        $container = new \CompiledContainer();
    } 
    else {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->addDefinitions(__DIR__."/../src/framework/Dependencies.php");
        $builder->enableCompilation(__DIR__."/../cache", "CompiledContainer");
        $container = $builder->build();
    }

    $router = new Router($container);
    $router->dispatch($httpMethod, $uri);
}

start();