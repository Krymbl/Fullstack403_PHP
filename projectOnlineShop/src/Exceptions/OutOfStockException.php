<?php

namespace ProjectOnlineShop\Exceptions;

class OutOfStockException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Товар не доступен: " . $message);
    }
}