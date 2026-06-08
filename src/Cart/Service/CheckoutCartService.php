<?php

namespace App\Cart\Service;

use App\Cart\Entity\CheckoutCart;
use App\Catalog\Repository\ProductRepositoryInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

readonly class CheckoutCartService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CheckoutCartSessionStorage $cartStorage,
    ) {
    }

    public function get(SessionInterface $session): CheckoutCart
    {
        return $this->cartStorage->get($session);
    }

    public function add(SessionInterface $session, string $productId, int $quantity): CheckoutCart
    {
        $this->assertProductExists($productId);

        $cart = $this->cartStorage->get($session)->add($productId, $quantity);
        $this->cartStorage->save($session, $cart);

        return $cart;
    }

    public function update(SessionInterface $session, string $productId, int $quantity): CheckoutCart
    {
        $this->assertProductExists($productId);

        $cart = $this->cartStorage->get($session)->update($productId, $quantity);
        $this->cartStorage->save($session, $cart);

        return $cart;
    }

    public function remove(SessionInterface $session, string $productId): CheckoutCart
    {
        $cart = $this->cartStorage->get($session)->remove($productId);
        $this->cartStorage->save($session, $cart);

        return $cart;
    }

    public function clear(SessionInterface $session): CheckoutCart
    {
        $this->cartStorage->clear($session);

        return $this->cartStorage->get($session);
    }

    private function assertProductExists(string $productId): void
    {
        if ($productId === '' || $this->productRepository->findOneById($productId) === null) {
            throw new InvalidArgumentException('Product was not found.');
        }
    }
}
