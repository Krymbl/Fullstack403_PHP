<?php

namespace ProjectOnlineShop\Repository;

use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\User;

class UserRepository
{
    private PDO $connection;

    public function __construct() {
        self::$connection = Database::getConnection();
    }

    public function save(User $user): int {
        try {
            $id = $user->getId();
            if ($id !== null && $this->findById($id) !== null) {
                $this->update($user);
            }
            $stmt = $this->connection->prepare(
                "INSERT INTO users (email, password_hash, role, name, surname, patronymic, phone ) 
                        VALUES (:email, :passwordHash, :role, :name, :surname, :patronymic, :phone)");
            $stmt->execute([
                'email' => $user->getEmail(),
                'passwordHash' => $user->getPasswordHash(),
                'role' => $user->getRole(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'patronymic' => $user->getPatronymic(),
                'phone' => $user->getPhone()
            ]);
        } catch (PDOException $e) {
            throw new DBException("не удалось сохранить сущность user c email: {$user->getEmail()}");
        } catch (DBException $e) {
            throw new DBException($e->getMessage());
        }
        return $this->connection->lastInsertId();
    }

    public function update(User $user): int {
        try {
            $stmt = $this->connection->prepare(
                "update users set role = :role, name = :name, surname = :surname, 
                 patronymic = :patronymic, phone = :phone where id = :id ");
            $stmt->execute([
                'role' => $user->getRole(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'patronymic' => $user->getPatronymic(),
                'phone' => $user->getPhone(),
                'id' => $user->getId()
            ]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new DBException("не удалось обновить сущность user c email: {$user->getEmail()}");
        }
    }

    public function updatePassword(User $user, string $newPassword): int {
        return 1;
    }

    public function updateEmail(User $user, string $newEmail): int {
        return 1;
    }


    public function delete(int $id): int
    {
        try {
            $stmt = $this->connection->prepare("delete from users where id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new DBException("не удалось удалить данные об user c айди: $id");
        }
    }

    public function findById(int $id): object
    {
        $stmt = $this->connection->prepare("select * from users where id = :id");
        $stmt->execute([
            'id' => $id
        ]);

        $userRaw = $stmt->fetch();
        $user = new User();
        $user->setId($userRaw['id']);
        $user->setEmail($userRaw['email']);
        $user->setRole($userRaw['role']);
    }

    public function findByEmail(string $email): object {

    }

    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }
}