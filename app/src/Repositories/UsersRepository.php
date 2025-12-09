<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Repository;
use App\Models\User;
use App\Framework\DataMapper;

use PDO;

class UsersRepository extends Repository implements IUsersRepository
{
    public function getAllUsers(): array
    {
        $sql = 'SELECT id, username, email, image_tokens, role FROM Users';
        $result = $this->connection->query($sql);
        $assocUsers = $result->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($assocUsers as $user){
            array_push($users, DataMapper::mapAssocUserToUser($user));
        }

        var_dump($users);

        return $users;
    }

    public function getUserByUserId(int $userId): ?User
    {
        return null;
    }

    public function updateUser(User $user)
    {
        return null;
    }

    public function createUser(User $user)
    {
        $stmt = $this->connection->prepare("INSERT INTO Users (username, email, password, image_tokens, role) 
            VALUES (:username, :email, :password, :image_tokens, :role)");

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

    public function deleteUserByUserId(int $id)
    {
        return null;
    }
}