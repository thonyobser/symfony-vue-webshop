<?php

namespace App\SimplePage\Repository;

use App\Shared\Infrastructure\Repository\RepositoryClientInterface;
use App\SimplePage\Entity\SimplePage;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: SimplePageRepositoryInterface::class)]
readonly class SimplePageRepository implements SimplePageRepositoryInterface
{
    private const COLLECTION = 'simple_pages';

    public function __construct(private RepositoryClientInterface $client)
    {
    }

    public function findOneBySlug(string $slug): ?SimplePage
    {
        foreach ($this->client->findAll(self::COLLECTION) as $item) {
            $page = SimplePage::fromArray($item);

            if ($page->slug() === $slug) {
                return $page;
            }
        }

        return null;
    }
}
