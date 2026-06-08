<?php

namespace App\Cart\Entity;

readonly class CheckoutCartItem
{
    private const MIN_QUANTITY = 1;

    public function __construct(
        private string $productId,
        private int $quantity,
    ) {
    }

    public static function create(string $productId, int $quantity): self
    {
        return new self($productId, max(self::MIN_QUANTITY, $quantity));
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function withQuantity(int $quantity): self
    {
        return self::create($this->productId, $quantity);
    }

    public function addQuantity(int $quantity): self
    {
        return self::create($this->productId, $this->quantity + max(self::MIN_QUANTITY, $quantity));
    }
}
