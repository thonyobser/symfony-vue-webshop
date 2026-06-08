<?php

namespace App\Catalog\Entity;

readonly class ProductImage
{
    public function __construct(private string $url)
    {
    }

    public static function fromString(string $url): self
    {
        return new self($url);
    }

    public function url(): string
    {
        return $this->url;
    }

}
