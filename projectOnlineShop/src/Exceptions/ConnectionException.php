<?php

namespace ProjectOnlineShop\Exceptions;

class ConnectionException extends \Exception
{

    public function __construct(string $message = "Не удалось подключиться к базе данных")
    {
        parent::__construct($message);
    }
}