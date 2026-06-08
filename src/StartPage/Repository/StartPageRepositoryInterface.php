<?php



namespace App\StartPage\Repository;

use App\StartPage\Entity\StartPage;

interface StartPageRepositoryInterface
{
    public function get(): StartPage;
}
