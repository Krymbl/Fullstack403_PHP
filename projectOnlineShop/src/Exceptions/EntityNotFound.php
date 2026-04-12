<?php

namespace ProjectOnlineShop\Exceptions;

class EntityNotFound extends \Exception
{
 public function __construct(string $entity)
 {
     parent::__construct("Не найдена сущность: " . $entity);
 }
}