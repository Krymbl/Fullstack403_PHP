<?php

namespace model;

class Cart {

    private array $items;
    //['id' => ['product' => Product, 'quantity' => int]]


    public function __construct() {
        $this->items = [];
    }

    public function add(Product $product, int $quantity = 1) : void {
        $id = $product->getId();
        if (array_key_exists($id, $this->items)) {
            $this->items[$id]["quantity"] += $quantity;
        } else {
            $this->items[$id] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
    }

    public function remove(int $productId) : void {
        $this->items[$productId]["quantity"] -= 1;
        if ($this->items[$productId]["quantity"] <= 0) {
            unset($this->items[$productId]);
        }
    }

    public function getItems() : array {
        return $this->items;
    }

    public function getTotal() : float {
        return array_reduce($this->items, function($carry, $item) {
            return $carry + ($item['product']->getPrice() * $item['quantity']);
        }, 0);
    }

    public function clear() : void {
        $this->items = [];
    }

}