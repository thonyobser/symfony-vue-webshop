<?php

namespace App\Cart\Dto;

readonly class CartDto
{
    /**
     * @param CartItemDto[] $items
     */
    public function __construct(
        private array $items,
        private int $count,
        private int $totalCents,
    ) {
    }

    public function toArray(): array
    {
        return [
            'items' => array_map(
                static fn (CartItemDto $item): array => $item->toArray(),
                $this->items,
            ),
            'count' => $this->count,
            'totalCents' => $this->totalCents,
        ];
    }
}
