<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class TestController extends AbstractController {
    #[Route('/test', name: 'test')]
    public function show(): Response
    {
        return $this->render('base.html.twig');
    }
}
