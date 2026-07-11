<?php

$loader = require __DIR__."/../vendor/autoload.php";

use App\Framework\Router;
use DI\ContainerBuilder;
use DI\CompiledContainer;

function start()
{
    var_dump(__DIR__);
    var_dump(realpath(__DIR__ . "/../"));
    var_dump(is_writable(__DIR__ . "/../"));

    if (file_exists(__DIR__ . "/../.env")) {
        $_ENV = parse_ini_file(__DIR__ . "/../.env");
    } 
    else {
        $_ENV = getenv();
    }
    
    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    require_once __DIR__ . "/../src/Exception/GlobalExceptionHandler.php";

    $globalExceptionHandler = new GlobalExceptionHandler();

    set_exception_handler([$globalExceptionHandler, "dispatch"]);

    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Expose-Headers: X-Auth-Error, Authorization");
    header("Access-Control-Allow-Origin: ". $_ENV["FRONT_END_URL"]); 
    header("Content-Type: application/json");

    if ($httpMethod === "OPTIONS") {
        http_response_code(204);
        exit();
    }

    session_start();

    $containerCacheFile = __DIR__."/../cache/CompiledContainer.php";

    if (file_exists($containerCacheFile)) {
        require_once $containerCacheFile;
        $container = new \CompiledContainer();
    } 
    else {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->addDefinitions(__DIR__."/../src/Framework/Dependencies.php");
        $builder->enableCompilation(__DIR__."/../cache", "CompiledContainer");
        $container = $builder->build();
    }

    $router = new Router($container);
    $router->dispatch($httpMethod, $uri);
}

start();