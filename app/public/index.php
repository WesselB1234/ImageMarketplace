<?php

$loader = require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use App\Framework\Router;
use App\Models\Exceptions\NotAllowedException;
use App\Models\Exceptions\NotFoundException;

function start()
{
    session_start();

    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->load();

    try{
        $httpMethod = $_SERVER["REQUEST_METHOD"];
        $uri = strtok($_SERVER["REQUEST_URI"], "?");

        $router = new Router();
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