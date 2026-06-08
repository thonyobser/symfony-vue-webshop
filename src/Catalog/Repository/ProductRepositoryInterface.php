<?php



namespace App\Catalog\Repository;

use App\Catalog\Entity\Product;

interface ProductRepositoryInterface
{
    public const DEFAULT_FEATURED_LIMIT = 4;

    /**
     * @return Product[]
     */
    public function all(): array;

    /**
     * @return Product[]
     */
    public function findFeatured(int $limit = self::DEFAULT_FEATURED_LIMIT): array;

    /**
     * @return Product[]
     */
    public function findByCategory(string $categorySlug): array;

    /**
     * @return Product[]
     */
    public function search(string $query): array;

    public function findOneBySlug(string $slug): ?Product;

    public function findOneById(string $id): ?Product;

    public function findOneByCategoryAndSlug(string $categorySlug, string $slug): ?Product;
}
