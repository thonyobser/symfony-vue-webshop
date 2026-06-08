<?php

namespace App\Wishlist\Service;

use App\Catalog\Repository\ProductRepositoryInterface;
use App\Wishlist\Entity\Wishlist;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

readonly class WishlistService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private WishlistSessionStorage $wishlistStorage,
    ) {
    }

    public function get(SessionInterface $session): Wishlist
    {
        return $this->wishlistStorage->get($session);
    }

    public function add(SessionInterface $session, string $productId): Wishlist
    {
        $this->assertProductExists($productId);

        $wishlist = $this->wishlistStorage->get($session)->add($productId);
        $this->wishlistStorage->save($session, $wishlist);

        return $wishlist;
    }

    public function remove(SessionInterface $session, string $productId): Wishlist
    {
        $wishlist = $this->wishlistStorage->get($session)->remove($productId);
        $this->wishlistStorage->save($session, $wishlist);

        return $wishlist;
    }

    public function clear(SessionInterface $session): Wishlist
    {
        $this->wishlistStorage->clear($session);

        return $this->wishlistStorage->get($session);
    }

    private function assertProductExists(string $productId): void
    {
        if ($productId === '' || $this->productRepository->findOneById($productId) === null) {
            throw new InvalidArgumentException('Product was not found.');
        }
    }
}
