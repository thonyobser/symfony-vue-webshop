<?php

namespace App\Cart\Service;

use App\Cart\Entity\CheckoutCart;
use App\Cart\Entity\CheckoutCartItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

readonly class CheckoutCartSessionStorage
{
    private const CART_SESSION_KEY = 'checkout_cart_items';

    public function get(SessionInterface $session): CheckoutCart
    {
        $items = $session->get(self::CART_SESSION_KEY, []);

        if (!is_array($items)) {
            return new CheckoutCart();
        }

        return $this->fromSessionItems($items);
    }

    public function save(SessionInterface $session, CheckoutCart $cart): void
    {
        if ($cart->isEmpty()) {
            $this->clear($session);

            return;
        }

        $session->set(self::CART_SESSION_KEY, $this->toSessionItems($cart));
    }

    public function clear(SessionInterface $session): void
    {
        $session->remove(self::CART_SESSION_KEY);
    }

    private function fromSessionItems(array $items): CheckoutCart
    {
        $cartItems = [];

        foreach ($items as $productId => $quantity) {
            if (is_string($productId) && is_int($quantity) && $quantity > 0) {
                $cartItems[$productId] = CheckoutCartItem::create($productId, $quantity);
            }
        }

        return new CheckoutCart($cartItems);
    }

    private function toSessionItems(CheckoutCart $cart): array
    {
        $items = [];

        foreach ($cart->items() as $item) {
            $items[$item->productId()] = $item->quantity();
        }

        return $items;
    }
}
