<?php

namespace ProjectOnlineShop\Repository;
/**
 * @template T
 */
interface Repository
{
    #public function save($entity): int;

    #public function update(object $entity): int;

    public function delete(int $id): int;

    public function findById(int $id): ?object;

    public function findAll(): array;
}