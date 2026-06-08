<?php

namespace App\Catalog\Entity;

use App\Shared\Entity\BreadcrumbItem;

readonly class Category
{
    public function __construct(
        private string $slug,
        private string $name,
        private string $title,
        private string $description,
        private string $bannerImage,
        private array $breadcrumbs,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            slug: $data['slug'],
            name: $data['name'],
            title: $data['title'],
            description: $data['description'],
            bannerImage: $data['bannerImage'],
            breadcrumbs: array_map(
                static fn (array $item): BreadcrumbItem => BreadcrumbItem::fromArray($item),
                $data['breadcrumbs'] ?? [],
            ),
        );
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function bannerImage(): string
    {
        return $this->bannerImage;
    }

    /**
     * @return BreadcrumbItem[]
     */
    public function breadcrumbs(): array
    {
        return $this->breadcrumbs;
    }
}
