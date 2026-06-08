<?php

namespace App\Checkout\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout_index', defaults: ['step' => 'cart'], methods: ['GET'])]
    #[Route('/checkout/{step}', name: 'app_checkout', requirements: ['step' => 'cart|shipping|payment|summary'], defaults: ['step' => 'cart'], methods: ['GET'])]
    public function __invoke(string $step): Response
    {
        return $this->render('checkout/checkout.html.twig', [
            'initialStep' => $step,
        ]);
    }
}
