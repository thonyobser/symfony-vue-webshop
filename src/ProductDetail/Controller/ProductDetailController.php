<?php



namespace App\ProductDetail\Controller;

use App\Catalog\Repository\CategoryRepositoryInterface;
use App\Catalog\Repository\ProductRepositoryInterface;
use App\Shared\Entity\BreadcrumbItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailController extends AbstractController
{
    #[Route('/{categorySlug}/{productSlug}', name: 'app_product_detail', methods: ['GET'])]
    public function __invoke(
        string $categorySlug,
        string $productSlug,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
    ): Response {
        $category = $categoryRepository->findOneBySlug($categorySlug);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category "%s" was not found.', $categorySlug));
        }

        $product = $productRepository->findOneByCategoryAndSlug($categorySlug, $productSlug);

        if ($product === null) {
            throw new NotFoundHttpException(sprintf('Product "%s" was not found.', $productSlug));
        }

        $breadcrumbs = array_merge($category->breadcrumbs(), [
            new BreadcrumbItem($product->name()),
        ]);

        return $this->render('product_detail/product_detail.html.twig', [
            'category' => $category,
            'product' => $product,
            'breadcrumbs' => $breadcrumbs,
            'features' => $product->features(),
            'tabs' => $product->tabs(),
        ]);
    }
}
