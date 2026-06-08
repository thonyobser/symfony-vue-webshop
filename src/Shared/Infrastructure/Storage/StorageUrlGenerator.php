<?php

namespace App\Shared\Infrastructure\Storage;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class StorageUrlGenerator
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function url(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return $this->urlGenerator->generate('app_storage_image', [
            'path' => ltrim($path, '/'),
        ]);
    }
}
