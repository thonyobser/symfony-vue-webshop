<?php

namespace App\Catalog\Repository;

use App\Catalog\Entity\Category;
use App\Shared\Infrastructure\Repository\RepositoryClientInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: CategoryRepositoryInterface::class)]
readonly class CategoryRepository implements CategoryRepositoryInterface
{
    private const COLLECTION = 'categories';

    public function __construct(private RepositoryClientInterface $client)
    {
    }

    public function all(): array
    {
        return array_map(
            static fn (array $item): Category => Category::fromArray($item),
            $this->client->findAll(self::COLLECTION),
        );
    }

    public function findOneBySlug(string $slug): ?Category
    {
        foreach ($this->all() as $category) {
            if ($category->slug() === $slug) {
                return $category;
            }
        }

        return null;
    }
}
