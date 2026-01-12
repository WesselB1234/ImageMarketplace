<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Repository;
use App\Models\User;
use App\Models\Helpers\DataMapper;
use App\Models\Exceptions\NotFoundException;

use PDO;

class UsersRepository extends Repository implements IUsersRepository
{
    public function getAllUsers(): array
    {
        $users = [];

        $stmt = $this->connection->prepare("SELECT user_id, username, image_tokens, role FROM Users;");
        $stmt->execute();
        $assocUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($assocUsers as $assocUser){
            array_push($users, DataMapper::mapAssocUserToUserWithoutPassword($assocUser));
        }

        return $users;
    }

    public function getUserByUserId(int $userId): ?User
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, image_tokens, role 
            FROM Users
            WHERE user_id = :userId;"
        );

        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT); 
        $stmt->execute();

        $assocUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assocUser !== false){
            return DataMapper::mapAssocUserToUserWithoutPassword($assocUser);
        }

        return null;
    }

    public function getUserByUsername(string $username): ?User
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, image_tokens, role 
            FROM Users
            WHERE username = :username;"
        );

        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        $assocUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assocUser !== false){
            return DataMapper::mapAssocUserToUserWithoutPassword($assocUser);
        }

        return null;
    }

    public function getFullyKnownUserByUsername(string $username): ?User
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, password, image_tokens, role 
            FROM Users
            WHERE username = :username;"
        );

        $stmt->bindValue(":username", $username, PDO::PARAM_STR); 
        $stmt->execute();

        $assocUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assocUser !== false){
            return DataMapper::mapAssocUserToUser($assocUser);
        }

        return null;
    }

    public function updateUser(User $user)
    {
        $stmt = $this->connection->prepare(
            "UPDATE Users 
            SET username = :username, "
                .($user->getPassword() !== null ? "password = :password, " : ""). 
                "image_tokens = :imageTokens, 
                role = :role
            WHERE user_id = :userId;"
        );

        $stmt->bindValue(":userId", $user->getUserId(), PDO::PARAM_INT); 
        $stmt->bindValue(":username", $user->getUsername(), PDO::PARAM_STR); 

        if ($user->getPassword() !== null){
            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
        }

        $stmt->bindValue(":imageTokens", $user->getImageTokens(), PDO::PARAM_INT); 
        $stmt->bindValue(":role", $user->getRole()->value, PDO::PARAM_STR);

        $stmt->execute();

        if($stmt->rowCount() == 0){
            throw new NotFoundException("User with ID ".$user->getUserId()." does not exist.");
        }
    }

    public function createUser(User $user): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO Users (username, password, image_tokens, role) 
            VALUES (:username, :password, :imageTokens, :role);"
        );

        $stmt->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);  
        $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR); 
        $stmt->bindValue(":imageTokens", $user->getImageTokens(), PDO::PARAM_INT); 
        $stmt->bindValue(":role", $user->getRole()->value, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$this->connection->lastInsertId();
    }

    public function updateTokensBalanceByUserId(int $userId, int $newTokensBalance)
    {
        $stmt = $this->connection->prepare(
            "UPDATE Users 
            SET image_tokens = :imageTokens
            WHERE user_id = :userId;"
        );

        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT); 
        $stmt->bindValue(":imageTokens", $newTokensBalance, PDO::PARAM_INT); 

        $stmt->execute();

        if($stmt->rowCount() == 0){
            throw new NotFoundException("User with ID ".$newTokensBalance." does not exist.");
        }
    }

    public function deleteUserByUserId(int $userId)
    {
        $stmt = $this->connection->prepare("DELETE FROM Users WHERE user_id = :userId;");
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT); 
        $stmt->execute();

        if($stmt->rowCount() === 0){
            throw new NotFoundException("User with id ".$userId." does not exist.");
        }
    }
}
