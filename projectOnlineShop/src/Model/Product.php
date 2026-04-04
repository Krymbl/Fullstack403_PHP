<?php

namespace ProjectOnlineShop\Model;

class Product {

    public function __construct(
        private int $id,
        private string $name,
        private string $description,
        private string $category, //categoryId
        private string $brand, //brandId
        private string $model, //modelId
        private int $price,
        private int $quantity,
        private bool $isAvailable,
        private string $imageUrl,
    ){}
}