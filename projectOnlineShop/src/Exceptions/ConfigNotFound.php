<?php

namespace ProjectOnlineShop\Exceptions;

class ConfigNotFound extends \Exception
{

    public function __construct(string $path)
    {
        parent::__construct("Конфиг не найден по пути: $path");
    }

}