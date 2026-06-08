<?php

namespace App\Search\Controller;

use App\Catalog\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    private const QUERY_KEY = 'q';

    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function __invoke(Request $request, ProductRepositoryInterface $productRepository): Response
    {
        $query = trim((string) $request->query->get(self::QUERY_KEY, ''));
        $products = $productRepository->search($query);

        return $this->render('search/search.html.twig', [
            'query' => $query,
            'products' => $products,
            'productCount' => count($products),
            'product_count' => count($products),
        ]);
    }
}
