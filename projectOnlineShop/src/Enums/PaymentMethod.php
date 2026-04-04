<?php

namespace ProjectOnlineShop\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CARD = 'card';
}