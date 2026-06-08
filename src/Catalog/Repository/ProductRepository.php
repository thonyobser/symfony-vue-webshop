<?php

namespace App\Catalog\Repository;

use App\Catalog\Entity\Product;
use App\Shared\Infrastructure\Repository\RepositoryClientInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: ProductRepositoryInterface::class)]
readonly class ProductRepository implements ProductRepositoryInterface
{
    private const COLLECTION = 'products';

    public function __construct(private RepositoryClientInterface $client)
    {
    }

    public function all(): array
    {
        return array_map(
            static fn (array $item): Product => Product::fromArray($item),
            $this->client->findAll(self::COLLECTION),
        );
    }

    public function findFeatured(int $limit = self::DEFAULT_FEATURED_LIMIT): array
    {
        $products = array_values(array_filter(
            $this->all(),
            static fn (Product $product): bool => $product->featured(),
        ));

        return array_slice($products, 0, $limit);
    }

    public function findByCategory(string $categorySlug): array
    {
        return array_values(array_filter(
            $this->all(),
            static fn (Product $product): bool => $product->categorySlug() === $categorySlug,
        ));
    }

    public function search(string $query): array
    {
        $normalizedQuery = strtolower(trim($query));

        if ($normalizedQuery === '') {
            return [];
        }

        return array_values(array_filter(
            $this->all(),
            static fn (Product $product): bool => str_contains(
                strtolower(implode(' ', [
                    $product->name(),
                    $product->subtitle() ?? '',
                    $product->slug(),
                    $product->shortDescription(),
                    $product->description(),
                ])),
                $normalizedQuery,
            ),
        ));
    }

    public function findOneBySlug(string $slug): ?Product
    {
        foreach ($this->all() as $product) {
            if ($product->slug() === $slug) {
                return $product;
            }
        }

        return null;
    }

    public function findOneById(string $id): ?Product
    {
        $item = $this->client->findOne(self::COLLECTION, $id);

        return $item === null ? null : Product::fromArray($item);
    }

    public function findOneByCategoryAndSlug(string $categorySlug, string $slug): ?Product
    {
        foreach ($this->all() as $product) {
            if ($product->categorySlug() === $categorySlug && $product->slug() === $slug) {
                return $product;
            }
        }

        return null;
    }
}
