<?php

namespace App\StartPage\Entity;

readonly class HeroSlide
{
    public function __construct(
        private string $title,
        private string $href,
        private string $desktopImage,
        private string $tabletImage,
        private string $mobileImage,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $image = $data['image'] ?? '';

        return new self(
            title: $data['title'],
            href: $data['href'] ?? $data['buttonUrl'] ?? '#',
            desktopImage: $data['desktopImage'] ?? $image,
            tabletImage: $data['tabletImage'] ?? $data['desktopImage'] ?? $image,
            mobileImage: $data['mobileImage'] ?? $data['tabletImage'] ?? $data['desktopImage'] ?? $image,
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    public function href(): string
    {
        return $this->href;
    }

    public function desktopImage(): string
    {
        return $this->desktopImage;
    }

    public function tabletImage(): string
    {
        return $this->tabletImage;
    }

    public function mobileImage(): string
    {
        return $this->mobileImage;
    }
}
