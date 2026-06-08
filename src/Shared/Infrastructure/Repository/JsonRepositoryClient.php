<?php

namespace App\Shared\Infrastructure\Repository;

use JsonException;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class JsonRepositoryClient implements RepositoryClientInterface
{
    private const DATA_DIRECTORY = 'data';
    private const JSON_MAX_DEPTH = 512;

    private array $cache = [];

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    public function findAll(string $collection): array
    {
        $items = $this->read(sprintf('%s.json', $collection));

        return array_is_list($items) ? $items : [$items];
    }

    public function findOne(string $collection, string $id): ?array
    {
        foreach ($this->findAll($collection) as $item) {
            if (($item['id'] ?? null) === $id) {
                return $item;
            }
        }

        return null;
    }

    private function read(string $fileName): array
    {
        $fileName = ltrim($fileName, '/');

        if (isset($this->cache[$fileName])) {
            return $this->cache[$fileName];
        }

        $relativePath = self::DATA_DIRECTORY . '/' . $fileName;
        $absolutePath = $this->projectDir . '/' . $relativePath;

        if (!is_file($absolutePath)) {
            throw new RuntimeException(sprintf('JSON data file "%s" was not found.', $relativePath));
        }

        $content = file_get_contents($absolutePath);

        if ($content === false) {
            throw new RuntimeException(sprintf('Could not read JSON data file "%s".', $relativePath));
        }

        try {
            $decoded = json_decode($content, true, self::JSON_MAX_DEPTH, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException(
                sprintf('Could not decode JSON data file "%s": %s', $relativePath, $exception->getMessage()),
                previous: $exception,
            );
        }

        if (!is_array($decoded)) {
            throw new RuntimeException(sprintf('JSON data file "%s" must decode to an array.', $relativePath));
        }

        return $this->cache[$fileName] = $decoded;
    }
}
