<?php

namespace ProjectOnlineShop\Repository;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\LoggerFactory;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\Cart;

/**
 * @implements Repository<Cart>
 */
class CartRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = LoggerFactory::getLogger();
    }

    public function save(Cart $cart): int
    {
        try {
            $id = $cart->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($cart);
            }

            $sql = "INSERT INTO cart (user_id, product_id, quantity)
                     VALUES (:userId, :productId, :quantity)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'userId' => $cart->getUserId(),
                'productId' => $cart->getProductId(),
                'quantity' => $cart->getQuantity(),
            ]);

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность Cart для пользователя с ID: {$cart->getUserId()}";
            $this->logger->error($message);
            throw new DBException($message);
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(Cart $cart): int
    {
        try {
            $sql = "UPDATE cart SET user_id = :userId, product_id = :productId, quantity = :quantity WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                'userId' => $cart->getUserId(),
                'productId' => $cart->getProductId(),
                'quantity' => $cart->getQuantity(),
                'id' => $cart->getId(),
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность Cart с ID: {$cart->getId()}";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM cart WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность Cart с ID: $id";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function findById(int $id): ?Cart
    {
        try {
            $sql = "SELECT * FROM cart WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToCart($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность Cart с ID: $id";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM cart";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToCart($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей Cart";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function findAllByUserId(int $userId): array
    {
        try {
            $sql = "SELECT * FROM cart WHERE user_id = :userId";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['userId' => $userId]);

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToCart($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить корзину пользователя с ID: $userId";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function deleteByUserId(int $userId): int
    {
        try {
            $sql = "DELETE FROM cart WHERE user_id = :userId";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['userId' => $userId]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось очистить корзину пользователя с ID: $userId";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    public function deleteByProductId(int $productId): int
    {
        try {
            $sql = "DELETE FROM cart WHERE produc_id = :productId";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['productId' => $productId]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить товар с ID: $productId";
            $this->logger->error($message);
            throw new DBException($message);
        }
    }

    private function mapToCart(array $row): Cart
    {
        return new Cart(
            $row['user_id'],
            $row['product_id'],
            $row['quantity'],
            $row['id'],
        );
    }
}