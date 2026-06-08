<?php

namespace App\SimplePage\Entity;

readonly class ContentBlock
{
    public function __construct(
        private string $title,
        private string $text,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            text: $data['text'],
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    public function text(): string
    {
        return $this->text;
    }
}
