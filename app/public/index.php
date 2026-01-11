<?php

$loader = require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use App\Framework\Router;
use App\Models\Exceptions\NotAllowedException;
use App\Models\Exceptions\NotFoundException;
use DI\ContainerBuilder;

function start()
{
    session_start();

    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->load();

    $builder = new ContainerBuilder(); 
    $builder->addDefinitions(__DIR__ . "/../src/framework/Dependencies.php"); 
    $container = $builder->build();

    $httpMethod = $_SERVER["REQUEST_METHOD"];
    $uri = strtok($_SERVER["REQUEST_URI"], "?");

    $router = new Router($container);

    try{
        $router->dispatch($httpMethod, $uri);
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

start();