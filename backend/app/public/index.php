<?php

$loader = require __DIR__."/../vendor/autoload.php";

use App\Framework\Router;
use App\Models\Exceptions\NotFoundException;
use DI\ContainerBuilder;
use DI\CompiledContainer;

function start()
{
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

    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    $router = new Router($container);

    try{
        $router->dispatch($httpMethod, $uri);
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

start();