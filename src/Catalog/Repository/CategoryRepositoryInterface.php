<?php



namespace App\Catalog\Repository;

use App\Catalog\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return \App\Catalog\Entity\Category[]
     */
    public function all(): array;

    public function findOneBySlug(string $slug): ?Category;
}
