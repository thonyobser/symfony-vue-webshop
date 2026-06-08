<?php

namespace App\Shared\Entity;

readonly class BreadcrumbItem
{
    public function __construct(
        private string $label,
        private string $url = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            url: $data['url'] ?? '',
        );
    }

    public function label(): string
    {
        return $this->label;
    }

    public function url(): string
    {
        return $this->url;
    }
}
