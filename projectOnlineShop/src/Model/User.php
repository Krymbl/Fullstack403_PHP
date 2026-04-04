<?php

namespace ProjectOnlineShop\Model;

class User {

    public function __construct(
        private int $id,
        private string $name,
        private string $surname,
        private string $patronymic,
        private string $email,
        private string $passwordHash,
        private string $role, //enum Role
        private string $phone
    ){}

}