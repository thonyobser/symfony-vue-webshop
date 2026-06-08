<?php

namespace App\Catalog\Entity;

readonly class ProductTabs
{
    public function __construct(
        private array $overview,
        private array $specifications,
        private array $inTheBox,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            overview: $data['overview'] ?? [],
            specifications: array_map(
                static fn (array $item): ProductSpecification => ProductSpecification::fromArray($item),
                $data['specifications'] ?? [],
            ),
            inTheBox: $data['in_the_box'] ?? [],
        );
    }

    /**
     * @return string[]
     */
    public function overview(): array
    {
        return $this->overview;
    }

    /**
     * @return ProductSpecification[]
     */
    public function specifications(): array
    {
        return $this->specifications;
    }

    /**
     * @return string[]
     */
    public function inTheBox(): array
    {
        return $this->inTheBox;
    }
}
