<?php

namespace App\Cart\Dto;

readonly class AddCartItemRequestDto
{
    public function __construct(
        private string $productId = '',
        private int $quantity = 1,
    ) {
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
