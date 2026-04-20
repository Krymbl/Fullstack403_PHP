<?php

namespace ProjectOnlineShop\Exceptions;

class ConfigMissingKeyException extends \Exception
{

    public function __construct(string $message) {
        parent::__construct("Пропущены обязательные поля: $message");
    }

}