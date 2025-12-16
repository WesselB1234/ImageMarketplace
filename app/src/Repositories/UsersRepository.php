<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Repository;
use App\Models\User;
use App\Framework\DataMapper;
use App\Models\Exceptions\NotFoundException;

use PDO;

class UsersRepository extends Repository implements IUsersRepository
{
    public function getAllUsers(): array
    {
        $sql = "SELECT user_id, username, email, image_tokens, role FROM Users;";
        $result = $this->connection->query($sql);
        $assocUsers = $result->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($assocUsers as $user){
            array_push($users, DataMapper::mapAssocUserToUser($user));
        }

        return $users;
    }

    public function getUserByUserId(int $userId): ?User
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, email, image_tokens, role 
            FROM Users
            WHERE user_id = :user_id;"
        );

        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        $assocUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assocUser !== false) {
            return DataMapper::mapAssocUserToUser($assocUser);
        }

        return null;
    }

    public function getUserByUsername(string $username): ?User
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, email, image_tokens, role 
            FROM Users
            WHERE username = :username;"
        );

        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $assocUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assocUser !== false) {
            return DataMapper::mapAssocUserToUser($assocUser);
        }

        return null;
    }

    public function getFullyKnownUserByUsername(string $username): ?User
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, email, password, image_tokens, role 
            FROM Users
            WHERE username = :username;"
        );

        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $assocUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assocUser !== false) {
            return DataMapper::mapAssocUserToFullyKnownUser($assocUser);
        }

        return null;
    }

    public function updateUser(User $user)
    {
        $stmt = $this->connection->prepare(
                "UPDATE Users 
                SET username = :username,
                    email = :email, 
                    password = :password, 
                    image_tokens = :image_tokens, 
                    role = :role
                WHERE user_id = :userId;"
            );

        $stmt->bindParam(':userId', $user->userId);
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':image_tokens', $user->imageTokens);
        $stmt->bindValue(':role', $user->role->value, PDO::PARAM_STR);

        $stmt->execute();

        if($stmt->rowCount() == 0)
        {
            throw new NotFoundException("User with id ".$user->userId." does not exist.");
        }
    }

    public function createUser(User $user)
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO Users (username, email, password, image_tokens, role) 
            VALUES (:username, :email, :password, :image_tokens, :role);"
        );

        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':image_tokens', $user->imageTokens);
        $stmt->bindValue(':role', $user->role->value, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function updateTokensBalanceByUserId(int $userId, int $newTokensBalance)
    {
        return null;
    }

    public function deleteUserByUserId(int $userId)
    {
        $stmt = $this->connection->prepare("DELETE FROM Users WHERE user_id = :userId;");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        if($stmt->rowCount() == 0)
        {
            throw new NotFoundException("User with id ".$userId." does not exist.");
        }
    }
}