<?php

namespace App\Cart\Entity;

readonly class CheckoutCart
{
    /**
     * @param array<string, CheckoutCartItem> $items
     */
    public function __construct(private array $items = [])
    {
    }

    public function add(string $productId, int $quantity): self
    {
        $items = $this->items;
        $items[$productId] = isset($items[$productId])
            ? $items[$productId]->addQuantity($quantity)
            : CheckoutCartItem::create($productId, $quantity);

        return new self($items);
    }

    public function update(string $productId, int $quantity): self
    {
        $items = $this->items;
        $items[$productId] = CheckoutCartItem::create($productId, $quantity);

        return new self($items);
    }

    public function remove(string $productId): self
    {
        $items = $this->items;
        unset($items[$productId]);

        return new self($items);
    }

    /**
     * @return CheckoutCartItem[]
     */
    public function items(): array
    {
        return array_values($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }
}
