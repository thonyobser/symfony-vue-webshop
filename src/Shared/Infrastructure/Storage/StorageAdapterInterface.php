<?php

namespace App\Shared\Infrastructure\Storage;

interface StorageAdapterInterface
{
    public function read(string $path): StoredObject;
}
