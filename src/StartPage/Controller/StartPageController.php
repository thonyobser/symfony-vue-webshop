<?php



namespace App\StartPage\Controller;

use App\Catalog\Repository\ProductRepositoryInterface;
use App\Shared\Repository\UspRepositoryInterface;
use App\StartPage\Repository\StartPageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StartPageController extends AbstractController
{
    #[Route('/', name: 'app_start_page', methods: ['GET'])]
    public function __invoke(
        StartPageRepositoryInterface $startPageRepository,
        ProductRepositoryInterface $productRepository,
        UspRepositoryInterface $uspRepository,
    ): Response {
        $page = $startPageRepository->get();
        $featuredProducts = $productRepository->findFeatured();

        return $this->render('start_page/start_page.html.twig', [
            'page' => $page,
            'heroSlides' => $page->heroSlides(),
            'featuredProducts' => $featuredProducts,
            'usps' => $uspRepository->findByArea('start_page'),
        ]);
    }
}
