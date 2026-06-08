<?php

namespace App\Wishlist\Dto;

readonly class AddWishlistItemRequestDto
{
    public function __construct(private string $productId = '')
    {
    }

    public function productId(): string
    {
        return $this->productId;
    }
}
