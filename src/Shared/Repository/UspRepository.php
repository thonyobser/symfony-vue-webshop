<?php

namespace App\Shared\Repository;

use App\Shared\Entity\Usp;
use App\Shared\Infrastructure\Repository\RepositoryClientInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: UspRepositoryInterface::class)]
readonly class UspRepository implements UspRepositoryInterface
{
    private const COLLECTION = 'usps';

    public function __construct(private RepositoryClientInterface $client)
    {
    }

    public function findByArea(string $area): array
    {
        return array_values(array_filter(
            $this->all(),
            static fn (Usp $usp): bool => $usp->isVisibleIn($area),
        ));
    }

    private function all(): array
    {
        return array_map(
            static fn (array $item): Usp => Usp::fromArray($item),
            $this->client->findAll(self::COLLECTION),
        );
    }
}
