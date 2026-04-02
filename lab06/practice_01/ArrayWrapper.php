<?php

class ArrayWrapper {

    public function __construct (
        private array $array
    ) { }

    public function __set(string $name, $value) : void {
        $this->array[$name] = $value;
    }

    public function __get (string $name) : mixed {
        return $this->array[$name] ?? null;
    }

    public function __isset (string $name) : bool {
        return isset($this->array[$name]);
    }

    public function __unset(string $name) : void {
        unset($this->array[$name]);
    }

    public function __invoke(mixed $key = null) : mixed {
        if ($key === null) {
            return $this->array;
        }
        return $this->array[$key] ?? null;
    }

    public function __clone() : void {
        foreach ($this->array as $name => $value) {
            if (is_object($value)) {
                $this->array[$name] = clone $value;
            }
        }
    }

    public function __toString() : string {
        return json_encode($this->array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}