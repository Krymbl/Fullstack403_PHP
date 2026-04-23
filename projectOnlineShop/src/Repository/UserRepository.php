<?php

namespace ProjectOnlineShop\Repository;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\Loggers\AppLoggerFactory;
use ProjectOnlineShop\Dto\UserDto;
use ProjectOnlineShop\Enums\Role;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\User;


class UserRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = AppLoggerFactory::getLogger();
    }

    public function save(User $user): int
    {
        try {
            $id = $user->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($user);
            }

            $sql = "INSERT INTO users (email, password_hash, role, name, surname, patronymic, phone)
                     VALUES (:email, :passwordHash, :role, :name, :surname, :patronymic, :phone)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'email' => $user->getEmail(),
                'passwordHash' => $user->getPasswordHash(),
                'role' => $user->getRole()->value,
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'patronymic' => $user->getPatronymic(),
                'phone' => $user->getPhone(),
            ]);

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность User с email: {$user->getEmail()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(User $user): int
    {
        try {
            $sql = "UPDATE users
                     SET role = :role, name = :name, surname = :surname,
                         patronymic = :patronymic, phone = :phone
                     WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'role' => $user->getRole()->value,
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'patronymic' => $user->getPatronymic(),
                'phone' => $user->getPhone(),
                'id' => $user->getId(),
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность User с ID: {$user->getId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function updatePassword(int $id, string $newPasswordHash): int
    {
        try {
            $sql = "UPDATE users SET password_hash = :passwordHash WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'passwordHash' => $newPasswordHash,
                'id' => $id,
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить пароль пользователя с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function updateEmail(int $id, string $newEmail): int
    {
        try {
            $sql = "UPDATE users SET email = :email WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'email' => $newEmail,
                'id' => $id,
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить email пользователя с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность User с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findById(int $id): ?UserDto
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToDto($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность User с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findByEmail(string $email): ?UserDto
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['email' => $email]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToDto($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность User с email: $email";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM users";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToDto($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей User";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    private function mapToDto(array $row): UserDto
    {
        return new UserDto(
            $row['email'],
            Role::from($row['role']),
            $row['name'],
            $row['surname'],
            $row['patronymic'],
            $row['phone'],
            $row['id'],
        );
    }
}