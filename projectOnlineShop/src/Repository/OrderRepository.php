<?php

namespace ProjectOnlineShop\Repository;

use DateTimeImmutable;
use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Core\Database;
use ProjectOnlineShop\Core\Loggers\AppLoggerFactory;
use ProjectOnlineShop\Enums\DeliveryType;
use ProjectOnlineShop\Enums\PaymentMethod;
use ProjectOnlineShop\Enums\Status;
use ProjectOnlineShop\Exceptions\DBException;
use ProjectOnlineShop\Model\Order;

/**
 * @implements Repository<Order>
 */
class OrderRepository implements Repository
{
    private PDO $connection;
    private Logger $logger;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->logger = AppLoggerFactory::getLogger();
    }

    public function save(Order $order): int
    {
        try {
            $id = $order->getId();

            if ($id !== null && $this->findById($id) !== null) {
                return $this->update($order);
            }

            $sql = "INSERT INTO orders
                        (user_id, total_price, status, delivery_type, payment_method,
                         first_name, last_name, patronymic, phone,
                         city, street, house, apartment, created_at)
                     VALUES
                        (:userId, :totalPrice, :status, :deliveryType, :paymentMethod,
                         :firstName, :lastName, :patronymic, :phone,
                         :city, :street, :house, :apartment, :createdAt)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute($this->mapToParams($order));

            return (int)$this->connection->lastInsertId();

        } catch (PDOException $e) {
            $message = "Не удалось сохранить сущность Order для пользователя с ID: {$order->getUserId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        } catch (DBException $e) {
            throw $e;
        }
    }

    public function update(Order $order): int
    {
        try {
            $sql = "UPDATE orders
                     SET user_id = :userId, total_price = :totalPrice, status = :status,
                         delivery_type = :deliveryType, payment_method = :paymentMethod,
                         first_name = :firstName, last_name = :lastName, patronymic = :patronymic,
                         phone = :phone, city = :city, street = :street, house = :house,
                         apartment = :apartment, created_at = :createdAt
                     WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $params = $this->mapToParams($order);
            $params['id'] = $order->getId();

            $stmt->execute($params);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось обновить сущность Order с ID: {$order->getId()}";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function delete(int $id): int
    {
        try {
            $sql = "DELETE FROM orders WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $message = "Не удалось удалить сущность Order с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findById(int $id): ?Order
    {
        try {
            $sql = "SELECT * FROM orders WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch();

            if ($row === false) {
                return null;
            }

            return $this->mapToOrder($row);

        } catch (PDOException $e) {
            $message = "Не удалось найти сущность Order с ID: $id";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM orders";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToOrder($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список всех сущностей Order";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    public function findAllByUserId(int $userId): array
    {
        try {
            $sql = "SELECT * FROM orders WHERE user_id = :userId ORDER BY created_at DESC";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['userId' => $userId]);

            $rows = $stmt->fetchAll();
            $result = [];

            foreach ($rows as $row) {
                $result[] = $this->mapToOrder($row);
            }

            return $result;

        } catch (PDOException $e) {
            $message = "Не удалось получить список заказов пользователя с ID: $userId";
            $ex = new DBException($message);
            $this->logger->error($ex->getMessage());
            throw $ex;
        }
    }

    private function mapToParams(Order $order): array
    {
        return [
            'userId' => $order->getUserId(),
            'totalPrice' => $order->getTotalPrice(),
            'status' => $order->getStatus()->value,
            'deliveryType' => $order->getDeliveryType()->value,
            'paymentMethod' => $order->getPaymentMethod()->value,
            'firstName' => $order->getFirstName(),
            'lastName' => $order->getLastName(),
            'patronymic' => $order->getPatronymic(),
            'phone' => $order->getPhone(),
            'city' => $order->getCity(),
            'street' => $order->getStreet(),
            'house' => $order->getHouse(),
            'apartment' => $order->getApartment(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    private function mapToOrder(array $row): Order
    {
        return new Order(
            $row['user_id'],
            $row['total_price'],
            $row['first_name'],
            $row['last_name'],
            $row['phone'],
            DeliveryType::from($row['delivery_type']),
            PaymentMethod::from($row['payment_method']),
            Status::from($row['status']),
            $row['patronymic'],
            $row['city'],
            $row['street'],
            $row['house'],
            $row['apartment'],
            $row['id'],
            new DateTimeImmutable($row['created_at']),
        );
    }
}