<?php



namespace App\Shared\Entity;

readonly class Usp
{
    public function __construct(
        private string $id,
        private string $title,
        private string $text,
        private string $icon,
        private array $areas,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            text: $data['text'],
            icon: $data['icon'],
            areas: $data['areas'] ?? [],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    /**
     * @return string[]
     */
    public function areas(): array
    {
        return $this->areas;
    }

    public function isVisibleIn(string $area): bool
    {
        return in_array($area, $this->areas, true);
    }
}
