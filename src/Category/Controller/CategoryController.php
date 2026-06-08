<?php



namespace App\Category\Controller;

use App\Catalog\Repository\CategoryRepositoryInterface;
use App\Catalog\Repository\ProductRepositoryInterface;
use App\Shared\Repository\UspRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function __invoke(
        string $slug,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        UspRepositoryInterface $uspRepository,
    ): Response {
        $category = $categoryRepository->findOneBySlug($slug);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category "%s" was not found.', $slug));
        }

        $products = $productRepository->findByCategory($category->slug());

        return $this->render('category/category.html.twig', [
            'category' => $category,
            'breadcrumbs' => $category->breadcrumbs(),
            'products' => $products,
            'productCount' => count($products),
            'product_count' => count($products),
            'usps' => $uspRepository->findByArea('category'),
        ]);
    }
}
