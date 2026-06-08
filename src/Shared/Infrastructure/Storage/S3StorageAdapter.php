<?php

namespace App\Shared\Infrastructure\Storage;

use RuntimeException;

readonly class S3StorageAdapter implements StorageAdapterInterface
{
    private object $client;

    public function __construct(
        private string $bucket = '',
        private string $region = 'eu-central-1',
        private string $prefix = '',
        private string $endpoint = '',
    ) {
        if (!class_exists('Aws\S3\S3Client')) {
            throw new RuntimeException('Install aws/aws-sdk-php to use S3StorageAdapter.');
        }

        if ($this->bucket === '') {
            throw new RuntimeException('Configure an S3 bucket before using S3StorageAdapter.');
        }

        $config = [
            'region' => $this->region,
            'version' => 'latest',
        ];

        if ($this->endpoint !== '') {
            $config['endpoint'] = $this->endpoint;
        }

        $className = 'Aws\S3\S3Client';
        $this->client = new $className($config);
    }

    public function read(string $path): StoredObject
    {
        $result = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $this->key($path),
        ]);

        return new StoredObject(
            (string) $result['Body'],
            (string) ($result['ContentType'] ?? 'application/octet-stream'),
        );
    }

    private function key(string $path): string
    {
        return trim($this->prefix . '/' . ltrim($path, '/'), '/');
    }
}
