<?php

namespace Practice_01\Exceptions;

class ConfigMissingKeyException extends \Exception
{

    public function __construct(string $message) {
        parent::__construct("Пропущено обязательные поля: " . $message);
    }

}