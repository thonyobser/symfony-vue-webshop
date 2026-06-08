<?php

namespace App\Shared\Infrastructure\Storage;

readonly class Storage
{
    public function __construct(private StorageAdapterInterface $adapter)
    {
    }

    public function read(string $path): StoredObject
    {
        return $this->adapter->read($path);
    }
}
