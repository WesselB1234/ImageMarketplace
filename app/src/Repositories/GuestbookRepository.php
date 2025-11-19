<?php

namespace App\Repositories;

use App\Config;
use PDO;

class GuestbookRepository
{
    private $connection;

    public function __construct() {
        $connection = $this->getConnectionObj();
    }

    private function getConnectionObj(){

        $connection = null;

        try {
            $connectionString = 'mysql:host=' . Config::DB_SERVER_NAME . ';dbname=' .
                Config::DB_NAME . ';charset=utf8mb4';

            $connection = new PDO(
                $connectionString,
                Config::DB_USERNAME,
                Config::DB_PASSWORD
            );

            $connection->setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            die('aww da connection failed >:(((((: ' . $e-getMessage());
        }

        return $connection;
    }

    public function getAll()
    {
        $connection = $this->getConnectionObj();

        $sql = 'SELECT id, posted_at, name, email, message FROM posts;';
        $result = $connection->query($sql);
        $posts = $result->fetchAll(\PDO::FETCH_ASSOC);

        return $posts;
    }

    public function addNewMessage(){

        $connection = $this->getConnectionObj();
        $stmt = $connection->prepare("INSERT INTO posts (name, email, message) VALUES (:name, :email, :message)");

        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $message = htmlspecialchars(trim($_POST['message'] ?? ''));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        $stmt->execute();
    }
}
