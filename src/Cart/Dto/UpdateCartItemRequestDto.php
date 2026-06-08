<?php

namespace App\Cart\Dto;

readonly class UpdateCartItemRequestDto
{
    public function __construct(private int $quantity = 1)
    {
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
