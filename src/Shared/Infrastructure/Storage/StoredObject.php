<?php

namespace App\Shared\Infrastructure\Storage;

readonly class StoredObject
{
    public function __construct(
        private string $content,
        private string $contentType,
    ) {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function contentType(): string
    {
        return $this->contentType;
    }
}
