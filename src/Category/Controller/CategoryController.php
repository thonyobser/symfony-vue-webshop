<?php



namespace App\Category\Controller;

use App\Catalog\Repository\CategoryRepositoryInterface;
use App\Catalog\Repository\ProductRepositoryInterface;
use App\Shared\Repository\UspRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    private const PRODUCTS_PER_PAGE = 4;

    #[Route('/category/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function __invoke(
        string $slug,
        Request $request,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        UspRepositoryInterface $uspRepository,
    ): Response {
        $category = $categoryRepository->findOneBySlug($slug);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category "%s" was not found.', $slug));
        }

        $products = $productRepository->findByCategory($category->slug());
        $productCount = count($products);
        $totalPages = max(1, (int) ceil($productCount / self::PRODUCTS_PER_PAGE));
        $currentPage = min(max(1, $request->query->getInt('page', 1)), $totalPages);
        $offset = ($currentPage - 1) * self::PRODUCTS_PER_PAGE;
        $visibleProducts = array_slice($products, $offset, self::PRODUCTS_PER_PAGE);
        $firstVisibleProduct = $productCount === 0 ? 0 : $offset + 1;
        $lastVisibleProduct = min($offset + count($visibleProducts), $productCount);

        return $this->render('category/category.html.twig', [
            'category' => $category,
            'breadcrumbs' => $category->breadcrumbs(),
            'products' => $visibleProducts,
            'productCount' => $productCount,
            'product_count' => $productCount,
            'listingSummary' => sprintf(
                'Showing %d-%d of %d %s',
                $firstVisibleProduct,
                $lastVisibleProduct,
                $productCount,
                strtolower($category->name()),
            ),
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'previousHref' => $currentPage > 1 ? $this->paginationHref($category->slug(), $request->query->all(), $currentPage - 1) : null,
                'nextHref' => $currentPage < $totalPages ? $this->paginationHref($category->slug(), $request->query->all(), $currentPage + 1) : null,
                'pageHrefs' => $this->paginationHrefs($category->slug(), $request->query->all(), $totalPages),
            ],
            'usps' => $uspRepository->findByArea('category'),
        ]);
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return array<int, string>
     */
    private function paginationHrefs(string $categorySlug, array $query, int $totalPages): array
    {
        $hrefs = [];

        for ($page = 1; $page <= $totalPages; $page++) {
            $hrefs[$page] = $this->paginationHref($categorySlug, $query, $page);
        }

        return $hrefs;
    }

    /**
     * @param array<string, mixed> $query
     */
    private function paginationHref(string $categorySlug, array $query, int $page): string
    {
        if ($page <= 1) {
            unset($query['page']);
        } else {
            $query['page'] = $page;
        }

        return $this->generateUrl('app_category_show', array_merge(['slug' => $categorySlug], $query));
    }
}
