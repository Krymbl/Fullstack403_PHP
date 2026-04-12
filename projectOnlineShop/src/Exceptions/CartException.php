<?php

namespace ProjectOnlineShop\Exceptions;

class CartException extends \Exception
{
    public function __construct(string $message = "Ошибка при работе с корзиной") {
        parent::__construct($message);
    }
}