<?php

namespace App\Wishlist\Dto;

readonly class WishlistDto
{
    /**
     * @param WishlistItemDto[] $items
     * @param string[] $productIds
     */
    public function __construct(
        private array $items,
        private array $productIds,
        private int $count,
    ) {
    }

    public function toArray(): array
    {
        return [
            'items' => array_map(
                static fn (WishlistItemDto $item): array => $item->toArray(),
                $this->items,
            ),
            'productIds' => $this->productIds,
            'count' => $this->count,
        ];
    }
}
