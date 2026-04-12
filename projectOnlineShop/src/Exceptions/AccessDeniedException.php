<?php

namespace ProjectOnlineShop\Exceptions;

class AccessDeniedException extends \Exception
{
    public function __construct() {
        parent::__construct("Отказано в доступе, нет прав");
    }

}