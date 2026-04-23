<?php

namespace ProjectOnlineShop\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        private string $path,
        private array  $methods = ["GET"],
    )
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethods(): array {
        return $this->methods;
    }


}