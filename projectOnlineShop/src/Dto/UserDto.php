<?php

namespace ProjectOnlineShop\Dto;

use ProjectOnlineShop\Enums\Role;

class UserDto
{

    public function __construct(
        private string  $email,
        private Role    $role = Role::USER,
        private ?string $name = null,
        private ?string $surname = null,
        private ?string $patronymic = null,
        private ?string $phone = null,
        private ?int    $id = null,
    )
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

}