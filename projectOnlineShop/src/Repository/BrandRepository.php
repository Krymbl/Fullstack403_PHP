<?php

namespace ProjectOnlineShop\Repository;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\Loggers\AppLoggerFactory;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\Brand;

/**
 * @implements Repository<Brand>
 */
class BrandRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = AppLoggerFactory::getLogger();;
    }

    public function save(Brand $brand): int
    {
        try {
            $id = $brand->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($brand);
            }

            $sql = "INSERT INTO brands (name) VALUES (:name)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'name' => $brand->getName(),
            ]);

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность Brand с именем: {$brand->getName()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(Brand $brand): int
    {
        try {
            $sql = "UPDATE brands SET name = :name WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'name' => $brand->getName(),
                'id' => $brand->getId(),
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность Brand с ID: {$brand->getId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM brands WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность Brand с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findById(int $id): ?Brand
    {
        try {
            $sql = "SELECT * FROM brands WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToBrand($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность Brand с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM brands";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToBrand($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей Brand";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    private function mapToBrand(array $row): Brand
    {
        return new Brand(
            $row['name'],
            $row['id'],
        );
    }
}