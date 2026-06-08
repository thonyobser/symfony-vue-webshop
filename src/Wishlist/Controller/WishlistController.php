<?php

namespace App\Wishlist\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'app_wishlist', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('wishlist/wishlist.html.twig');
    }
}
