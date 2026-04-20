<?php

class Container
{
    public function __construct()
    {

    }

    public function get(string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();
        if (is_null($constructor)) {
            $constructorParams = [];
        } else {
            $constructorParams = $constructor->getParameters();
        }

        $dependencies = [];

        foreach ($constructorParams as $param) {
            if ($param->hasType()) {
                $type = $param->getType();
                if (!$type->isBuiltin() && class_exists($type->getName())) {
                    $dependencies[] = $this->get($type->getName());
                }

            }
        }

        if (empty($dependencies)) {
            $object = $reflectionClass->newInstance();
        } else {
            $object = $reflectionClass->newInstanceArgs($dependencies);
        }

        return $object;
    }



}