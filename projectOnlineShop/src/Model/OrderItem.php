<?php

namespace ProjectOnlineShop\Model;

class OrderItem {

    public function __construct(
        private int $id,
        private int $orderId,
        private int $productId,
        private int $quantity,
        private int $price,
    ){}
}