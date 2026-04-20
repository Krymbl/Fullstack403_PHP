<?php

namespace ProjectOnlineShop\Repository;

interface Repository
{
    public function save($entity): int;

    public function delete(int $id): int;

    public function update($entity): int;

    public function findById(int $id): object;

    public function findAll(): array;
}