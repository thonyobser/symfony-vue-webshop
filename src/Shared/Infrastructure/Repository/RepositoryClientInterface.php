<?php

namespace App\Shared\Infrastructure\Repository;

interface RepositoryClientInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(string $collection): array;

    /**
     * @return array<string, mixed>|null
     */
    public function findOne(string $collection, string $id): ?array;
}
