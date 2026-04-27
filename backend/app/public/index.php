<?php

$loader = require __DIR__."/../vendor/autoload.php";

use App\Framework\Router;
use App\Models\Exceptions\NotFoundException;
use DI\ContainerBuilder;
use DI\CompiledContainer;

function start()
{
    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
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

    try{
        $router->dispatch($httpMethod, $uri);
    }
    catch(NotFoundException $e){
        error_log($e->getMessage());
        http_response_code(404);
        echo $e->getMessage(); 
    }
    catch(Exception $e){ 
        error_log($e->getMessage());
        http_response_code(400);
        echo $e->getMessage(); 
    }
}

start();