<?php

namespace App\Wishlist\Entity;

readonly class Wishlist
{
    /**
     * @param string[] $productIds
     */
    public function __construct(private array $productIds = [])
    {
    }

    /**
     * @param string[] $productIds
     */
    public static function fromProductIds(array $productIds): self
    {
        $normalizedProductIds = [];

        foreach ($productIds as $productId) {
            if (is_string($productId) && $productId !== '') {
                $normalizedProductIds[$productId] = $productId;
            }
        }

        return new self($normalizedProductIds);
    }

    public function add(string $productId): self
    {
        $productIds = $this->productIds;
        $productIds[$productId] = $productId;

        return new self($productIds);
    }

    public function remove(string $productId): self
    {
        $productIds = $this->productIds;
        unset($productIds[$productId]);

        return new self($productIds);
    }

    public function contains(string $productId): bool
    {
        return isset($this->productIds[$productId]);
    }

    /**
     * @return string[]
     */
    public function productIds(): array
    {
        return array_values($this->productIds);
    }

    public function count(): int
    {
        return count($this->productIds);
    }

    public function isEmpty(): bool
    {
        return $this->productIds === [];
    }
}
