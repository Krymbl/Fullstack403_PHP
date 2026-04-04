<?php

namespace ProjectOnlineShop\Enums;
enum Status: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
}