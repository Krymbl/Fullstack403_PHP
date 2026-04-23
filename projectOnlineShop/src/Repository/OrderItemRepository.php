<?php

namespace ProjectOnlineShop\Repository;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\Loggers\AppLoggerFactory;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\OrderItem;

/**
 * @implements Repository<OrderItem>
 */
class OrderItemRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = AppLoggerFactory::getLogger();
    }

    public function save(OrderItem $orderItem): int
    {
        try {
            $id = $orderItem->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($orderItem);
            }

            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
                     VALUES (:orderId, :productId, :quantity, :price)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute($this->mapToParams($orderItem));

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность OrderItem для заказа с ID: {$orderItem->getOrderId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(OrderItem $orderItem): int
    {
        try {
            $sql = "UPDATE order_items
                     SET order_id = :orderId, product_id = :productId,
                         quantity = :quantity, price = :price
                     WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $params = $this->mapToParams($orderItem);
            $params['id'] = $orderItem->getId();

            $stmt->execute($params);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность OrderItem с ID: {$orderItem->getId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM order_items WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность OrderItem с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findById(int $id): ?OrderItem
    {
        try {
            $sql = "SELECT * FROM order_items WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToOrderItem($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность OrderItem с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM order_items";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToOrderItem($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей OrderItem";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAllByOrderId(int $orderId): array
    {
        try {
            $sql = "SELECT * FROM order_items WHERE order_id = :orderId";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['orderId' => $orderId]);

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToOrderItem($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить позиции заказа с ID: $orderId";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }
    public function findAllByUserId(int $userId): array
    {
        try {
            $sql = "SELECT oi.* FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = :userId";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['userId' => $userId]);

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToOrderItem($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить позиции заказа с ID юзера: $userId";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    private function mapToParams(OrderItem $orderItem): array
    {
        return [
            'orderId' => $orderItem->getOrderId(),
            'productId' => $orderItem->getProductId(),
            'quantity' => $orderItem->getQuantity(),
            'price' => $orderItem->getPrice(),
        ];
    }

    private function mapToOrderItem(array $row): OrderItem
    {
        return new OrderItem(
            $row['order_id'],
            $row['product_id'],
            $row['quantity'],
            $row['price'],
            $row['id'],
        );
    }
}