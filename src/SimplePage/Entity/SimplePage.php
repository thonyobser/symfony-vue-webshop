<?php



namespace App\SimplePage\Entity;

use App\Shared\Entity\BreadcrumbItem;

readonly class SimplePage
{
    public function __construct(
        private string $slug,
        private string $title,
        private string $eyebrow,
        private string $intro,
        private array $breadcrumbs,
        private array $contentBlocks,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            slug: $data['slug'],
            title: $data['title'],
            eyebrow: $data['eyebrow'] ?? '',
            intro: $data['intro'],
            breadcrumbs: array_map(
                static fn (array $item): BreadcrumbItem => BreadcrumbItem::fromArray($item),
                $data['breadcrumbs'] ?? [],
            ),
            contentBlocks: array_map(
                static fn (array $block): ContentBlock => ContentBlock::fromArray($block),
                $data['contentBlocks'] ?? [],
            ),
        );
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function eyebrow(): string
    {
        return $this->eyebrow;
    }

    public function intro(): string
    {
        return $this->intro;
    }

    /**
     * @return BreadcrumbItem[]
     */
    public function breadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    /**
     * @return ContentBlock[]
     */
    public function contentBlocks(): array
    {
        return $this->contentBlocks;
    }
}
