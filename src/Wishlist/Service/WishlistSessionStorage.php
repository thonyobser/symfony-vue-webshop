<?php

namespace App\Wishlist\Service;

use App\Wishlist\Entity\Wishlist;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

readonly class WishlistSessionStorage
{
    private const WISHLIST_SESSION_KEY = 'wishlist_product_ids';

    public function get(SessionInterface $session): Wishlist
    {
        $productIds = $session->get(self::WISHLIST_SESSION_KEY, []);

        if (!is_array($productIds)) {
            return new Wishlist();
        }

        return Wishlist::fromProductIds($productIds);
    }

    public function save(SessionInterface $session, Wishlist $wishlist): void
    {
        if ($wishlist->isEmpty()) {
            $this->clear($session);

            return;
        }

        $session->set(self::WISHLIST_SESSION_KEY, $wishlist->productIds());
    }

    public function clear(SessionInterface $session): void
    {
        $session->remove(self::WISHLIST_SESSION_KEY);
    }
}
