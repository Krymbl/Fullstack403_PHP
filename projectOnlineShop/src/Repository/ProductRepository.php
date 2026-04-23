<?php

namespace ProjectOnlineShop\Repository;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\Loggers\AppLoggerFactory;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\Product;

/**
 * @implements Repository<Product>
 */
class ProductRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = AppLoggerFactory::getLogger();
    }

    public function save(Product $product): int
    {
        try {
            $id = $product->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($product);
            }

            $sql = "INSERT INTO products
                        (name, category_id, brand_id, price, quantity, is_available, model, description, image_url)
                     VALUES
                        (:name, :categoryId, :brandId, :price, :quantity, :isAvailable, :model, :description, :imageUrl)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute($this->mapToParams($product));

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность Product с именем: {$product->getName()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(Product $product): int
    {
        try {
            $sql = "UPDATE products
                     SET name = :name, category_id = :categoryId, brand_id = :brandId,
                         price = :price, quantity = :quantity, is_available = :isAvailable,
                         model = :model, description = :description, image_url = :imageUrl
                     WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $params = $this->mapToParams($product);
            $params['id'] = $product->getId();

            $stmt->execute($params);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность Product с ID: {$product->getId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность Product с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findById(int $id): ?Product
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToProduct($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность Product с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAllByFilter(
        ?string $name = null,
        ?int  $minPrice = null,
        ?int  $maxPrice = null,
        ?string $model = null,
        ?int $categoryId = null,
        ?int $brandId = null,
    ): array //TODO изменить полностью добавить поиск по названию
    {
        try {
            $sql = "SELECT * FROM products WHERE is_available = true AND quantity > 0";
            $params = [];

            if ($name !== null) {
                $sql .= " AND name ILIKE :name";
                $params["name"] = "%{$name}%";
            }
            if ($model !== null) {
                $sql .= " AND model ILIKE :model";
                $params["model"] = "%{$model}%";
            }

            if ($categoryId !== null) {
                $sql .= " AND category_id = :categoryId";
                $params['categoryId'] = $categoryId;
            }

            if ($brandId !== null) {
                $sql .= " AND brand_id = :brandId";
                $params['brandId'] = $brandId;
            }

            if ($minPrice !== null) {
                $sql .= " AND price >= :minPrice";
                $params['minPrice'] = $minPrice;
            }

            if ($maxPrice !== null) {
                $sql .= " AND price <= :maxPrice";
                $params['maxPrice'] = $maxPrice;
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToProduct($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список продуктов по фильтру";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM products";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToProduct($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей Product";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    private function mapToParams(Product $product): array
    {
        return [
            'name' => $product->getName(),
            'categoryId' => $product->getCategoryId(),
            'brandId' => $product->getBrandId(),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'isAvailable' => $product->isAvailable() ? 1 : 0,
            'model' => $product->getModel(),
            'description' => $product->getDescription(),
            'imageUrl' => $product->getImageUrl(),
        ];
    }

    private function mapToProduct(array $row): Product
    {
        return new Product(
            $row['name'],
            $row['category_id'],
            $row['brand_id'],
            $row['price'],
            $row['quantity'],
            (bool)$row['is_available'],
            $row['id'],
            $row['model'],
            $row['description'],
            $row['image_url'],
        );
    }
}