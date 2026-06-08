<?php

namespace App\StartPage\Repository;

use App\Shared\Infrastructure\Repository\RepositoryClientInterface;
use App\StartPage\Entity\StartPage;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: StartPageRepositoryInterface::class)]
readonly class StartPageRepository implements StartPageRepositoryInterface
{
    private const COLLECTION = 'start_page';

    public function __construct(private RepositoryClientInterface $client)
    {
    }

    public function get(): StartPage
    {
        return StartPage::fromArray($this->client->findAll(self::COLLECTION)[0] ?? []);
    }
}
