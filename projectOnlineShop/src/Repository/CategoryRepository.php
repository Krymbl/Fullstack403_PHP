<?php

namespace ProjectOnlineShop\Repository;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\LoggerFactory;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\Category;

/**
 * @implements Repository<Category>
 */
class CategoryRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = LoggerFactory::getLogger();
    }

    public function save(Category $category): int
    {
        try {
            $id = $category->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($category);
            }

            $sql = "INSERT INTO categories (name) VALUES (:name)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'name' => $category->getName(),
            ]);

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность Category с именем: {$category->getName()}";
            $this->logger->error($message);
            throw new DBException($message);
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(Category $category): int
    {
        try {
            $sql = "UPDATE categories SET name = :name WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'name' => $category->getName(),
                'id' => $category->getId(),
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность Category с ID: {$category->getId()}";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM categories WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность Category с ID: $id";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function findById(int $id): ?Category
    {
        try {
            $sql = "SELECT * FROM categories WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToCategory($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность Category с ID: $id";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM categories";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToCategory($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей Category";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    private function mapToCategory(array $row): Category
    {
        return new Category(
            $row['name'],
            $row['id'],
        );
    }
}