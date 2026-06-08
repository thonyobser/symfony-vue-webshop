<?php

namespace App\Catalog\Entity;

readonly class ProductFeature
{
    public const DEFAULT_ICON = 'check-circle';

    public function __construct(
        private string $label,
        private string $icon = self::DEFAULT_ICON,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            icon: $data['icon'] ?? self::DEFAULT_ICON,
        );
    }

    public function label(): string
    {
        return $this->label;
    }

    public function icon(): string
    {
        return $this->icon;
    }
}
