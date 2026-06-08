<?php

namespace App\Wishlist\Dto;

readonly class WishlistItemDto
{
    public function __construct(
        private string $id,
        private string $name,
        private int $priceCents,
        private string $image,
        private string $url,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'priceCents' => $this->priceCents,
            'image' => $this->image,
            'url' => $this->url,
        ];
    }
}
