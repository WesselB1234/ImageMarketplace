<?php

namespace App\Repositories;

use PDO;

class Repository 
{
    protected PDO $connection;

    public function __construct(){

        $connectionString = 'mysql:host=' . $_ENV["DB_SERVER_NAME"] . ';dbname=' .
            $_ENV["DB_NAME"] . ';charset=utf8mb4';

        $this->connection = new PDO(
            $connectionString,
            $_ENV["DB_USERNAME"],
            $_ENV["DB_PASSWORD"]
        );

        $this->connection->setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}