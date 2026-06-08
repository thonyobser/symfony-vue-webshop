<?php



namespace App\Shared\Repository;

use App\Shared\Entity\Usp;

interface UspRepositoryInterface
{
    /** @return list<Usp> */
    /**
     * @return \App\Shared\Entity\Usp[]
     */
    public function findByArea(string $area): array;
}
