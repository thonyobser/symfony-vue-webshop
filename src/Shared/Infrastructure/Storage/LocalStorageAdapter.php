<?php

namespace App\Shared\Infrastructure\Storage;

use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class LocalStorageAdapter implements StorageAdapterInterface
{
    private const IMAGE_DIRECTORY = 'data/images';

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
    ) {
    }

    public function read(string $path): StoredObject
    {
        $relativePath = $this->normalizePath($path);
        $absolutePath = $this->projectDir . '/' . self::IMAGE_DIRECTORY . '/' . $relativePath;
        $realImageDirectory = realpath($this->projectDir . '/' . self::IMAGE_DIRECTORY);
        $realPath = realpath($absolutePath);

        if ($realImageDirectory === false || $realPath === false || !str_starts_with($realPath, $realImageDirectory)) {
            throw new RuntimeException(sprintf('Image "%s" was not found.', $relativePath));
        }

        $content = file_get_contents($realPath);

        if ($content === false) {
            throw new RuntimeException(sprintf('Could not read image "%s".', $relativePath));
        }

        return new StoredObject($content, $this->contentType($realPath));
    }

    private function normalizePath(string $path): string
    {
        return ltrim($path, '/');
    }

    private function contentType(string $path): string
    {
        $contentType = mime_content_type($path);

        return $contentType === false ? 'application/octet-stream' : $contentType;
    }
}
