<?php

namespace ProjectOnlineShop\Core;

use ProjectOnlineShop\Attributes\Route;
use ReflectionClass;
use ReflectionMethod;

class Router
{
    private array $routes = [];

    public function __construct()
    {
    }

    public function register(array $controllerClasses): void
    {
        foreach ($controllerClasses as $controller) {
            $this->registerControllerRoutes($controller);
        }
    }

    private function registerControllerRoutes(string $controllerClass): void
    {
        $reflectionClass = new ReflectionClass($controllerClass);
        $reflectionMethods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($reflectionMethods as $reflectionMethod) {
            $attributes = $reflectionMethod->getAttributes(Route::class);

            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();

                $path = $route->getPath();
                $methods = $route->getMethods();

                foreach ($methods as $httpMethod) {
                    $this->addRoute($httpMethod, $path, [$controllerClass, $reflectionMethod->getName()]);
                }
            }
        }
    }

    public function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

//    public function addRoutes(array $routes): void
//    {
//        $this->routes = $routes;
//    }

    public function getRoutes(): array
    {
        return $this->routes;
    }


}