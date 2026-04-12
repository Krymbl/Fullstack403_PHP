<?php

namespace ProjectOnlineShop\Exceptions;

class AuthException extends \Exception
{
    public function __construct(string $message = "Неправильная авторизация, не совпадает логин или пароль")
    {
        parent::__construct($message);
    }
}