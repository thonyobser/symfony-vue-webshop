<?php

namespace App\Shared\Twig;

use App\Shared\Infrastructure\Storage\StorageUrlGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StorageExtension extends AbstractExtension
{
    public function __construct(private readonly StorageUrlGenerator $storageUrlGenerator)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('storage_url', [$this->storageUrlGenerator, 'url']),
        ];
    }
}
