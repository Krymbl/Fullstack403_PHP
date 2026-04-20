<?php

namespace ProjectOnlineShop\Exceptions;

class DBException extends \Exception
{
 public function __construct(string $message)
 {
     parent::__construct("Проблема при запросе в базу данных: $message");
 }
}