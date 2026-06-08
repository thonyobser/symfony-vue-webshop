<?php

namespace App\Catalog\Entity;

readonly class ProductSpecification
{
    public function __construct(
        private string $label,
        private string $value,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            value: $data['value'],
        );
    }

    public function label(): string
    {
        return $this->label;
    }

    public function value(): string
    {
        return $this->value;
    }
}
