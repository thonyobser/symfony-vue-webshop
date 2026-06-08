<?php



namespace App\SimplePage\Repository;

use App\SimplePage\Entity\SimplePage;

interface SimplePageRepositoryInterface
{
    public function findOneBySlug(string $slug): ?SimplePage;
}
