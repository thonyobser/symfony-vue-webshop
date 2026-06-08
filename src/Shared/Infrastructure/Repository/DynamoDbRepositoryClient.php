<?php

namespace App\Shared\Infrastructure\Repository;

use RuntimeException;

readonly class DynamoDbRepositoryClient implements RepositoryClientInterface
{
    private object $client;

    public function __construct(
        private string $region = 'eu-central-1',
        private string $tablePrefix = '',
        private string $endpoint = '',
    ) {
        if (!class_exists('Aws\DynamoDb\DynamoDbClient')) {
            throw new RuntimeException('Install aws/aws-sdk-php to use DynamoDbRepositoryClient.');
        }

        $config = [
            'region' => $this->region,
            'version' => 'latest',
        ];

        if ($this->endpoint !== '') {
            $config['endpoint'] = $this->endpoint;
        }

        $className = 'Aws\DynamoDb\DynamoDbClient';
        $this->client = new $className($config);
    }

    public function findAll(string $collection): array
    {
        $items = [];
        $request = [
            'TableName' => $this->tableName($collection),
        ];

        do {
            $result = $this->client->scan($request);
            $items = array_merge($items, $result['Items'] ?? []);

            if (isset($result['LastEvaluatedKey'])) {
                $request['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
            } else {
                unset($request['ExclusiveStartKey']);
            }
        } while (isset($request['ExclusiveStartKey']));

        return array_map(
            fn (array $item): array => $this->normalizeItem($item),
            $items,
        );
    }

    public function findOne(string $collection, string $id): ?array
    {
        $result = $this->client->getItem([
            'TableName' => $this->tableName($collection),
            'Key' => [
                'id' => ['S' => $id],
            ],
        ]);

        if (!isset($result['Item']) || !is_array($result['Item'])) {
            return null;
        }

        return $this->normalizeItem($result['Item']);
    }

    private function tableName(string $collection): string
    {
        return $this->tablePrefix . $collection;
    }

    private function normalizeItem(array $item): array
    {
        $normalized = [];

        foreach ($item as $key => $value) {
            $normalized[$key] = $this->normalizeValue($value);
        }

        return $normalized;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (!is_array($value) || count($value) !== 1) {
            return $value;
        }

        $type = array_key_first($value);
        $rawValue = $value[$type];

        return match ($type) {
            'S' => $rawValue,
            'N' => str_contains((string) $rawValue, '.') ? (float) $rawValue : (int) $rawValue,
            'SS' => $rawValue,
            'NS' => array_map(
                static fn (string $item): int|float => str_contains($item, '.') ? (float) $item : (int) $item,
                $rawValue,
            ),
            'BOOL' => (bool) $rawValue,
            'NULL' => null,
            'L' => array_map(fn (mixed $item): mixed => $this->normalizeValue($item), $rawValue),
            'M' => $this->normalizeItem($rawValue),
            default => $rawValue,
        };
    }
}
