<?php

namespace ProjectOnlineShop\Model;

class Cart {

    public function __construct(
        private int $id,
        private int $userId,
        private int $productId,
        private int $quantity,
    ){}

}