<?php

namespace ProjectOnlineShop\Model;

use DateTimeImmutable;
use ProjectOnlineShop\Enums\DeliveryType;
use ProjectOnlineShop\Enums\PaymentMethod;
use ProjectOnlineShop\Enums\Status;

class Order
{
    public function __construct(
        private int               $userId,
        private int               $totalPrice,
        private string            $firstName,
        private string            $lastName,
        private string            $phone,
        private DeliveryType      $deliveryType = DeliveryType::PICKUP,
        private PaymentMethod     $paymentMethod = PaymentMethod::CASH,
        private Status            $status = Status::NEW,
        private ?string           $patronymic = null,
        private ?string           $city = null,
        private ?string           $street = null,
        private ?string           $house = null,
        private ?string           $apartment = null,
        private ?int              $id = null,
        private DateTimeImmutable $createdAt = new DateTimeImmutable(),
    )
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getDeliveryType(): DeliveryType
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(DeliveryType $deliveryType): void
    {
        $this->deliveryType = $deliveryType;
    }

    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getHouse(): ?string
    {
        return $this->house;
    }

    public function setHouse(?string $house): void
    {
        $this->house = $house;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function setApartment(?string $apartment): void
    {
        $this->apartment = $apartment;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}