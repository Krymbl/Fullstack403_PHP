<?php

namespace ProjectOnlineShop\Core;

class Router
{
    private array $routes = [];

    public function __construct(){}

    public function addRoutes(array $routes): void {
        $this->routes = $routes;
    }

    public function getRoutes(): array {
        return $this->routes;
    }

}