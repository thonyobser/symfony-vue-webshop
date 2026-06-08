<?php

namespace App\StartPage\Entity;

readonly class StartPage
{
    public function __construct(
        private string $title,
        private string $metaDescription,
        private array $heroSlides,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            metaDescription: $data['metaDescription'],
            heroSlides: array_map(
                static fn (array $slide): HeroSlide => HeroSlide::fromArray($slide),
                $data['heroSlides'] ?? [],
            ),
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    public function metaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * @return HeroSlide[]
     */
    public function heroSlides(): array
    {
        return $this->heroSlides;
    }
}
