<?php

namespace App\Cart\Dto;

readonly class CartItemDto
{
    public function __construct(
        private string $id,
        private string $name,
        private int $priceCents,
        private int $lineTotalCents,
        private string $image,
        private string $url,
        private int $quantity,
    ) {
    }

    public function lineTotalCents(): int
    {
        return $this->lineTotalCents;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'priceCents' => $this->priceCents,
            'lineTotalCents' => $this->lineTotalCents,
            'image' => $this->image,
            'url' => $this->url,
            'quantity' => $this->quantity,
        ];
    }
}
