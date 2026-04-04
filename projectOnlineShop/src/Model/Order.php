<?php

namespace ProjectOnlineShop\Model;
use DateTimeImmutable;

class Order {

    public function __construct(
        private int $id,
        private int $userId,
        private int $totalPrice,
        private string $status,          // enum Status
        private string $deliveryType,    // enum DeliveryType
        private string $paymentMethod,   // enum PaymentMethod
        private string $firstName,
        private string $lastName,
        private string $patronymic,
        private string $phone,
        private string $city,
        private string $street,
        private string $house,
        private string $apartment,
        private DateTimeImmutable $createdAt,
    ){}

    //Прочитать про nullable. Можно null как дефолтное значение ставить в атрибутах. Не успел разобраться

}