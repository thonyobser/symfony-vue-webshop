<?php



namespace App\Catalog\Entity;

readonly class Product
{
    public const DEFAULT_CURRENCY = 'USD';
    public const DEFAULT_SHIPPING_TEXT = 'Ships within 1–2 business days';
    public const STATUS_IN_STOCK = 'in_stock';

    private const CENTS_PER_UNIT = 100;

    public function __construct(
        private string $id,
        private string $slug,
        private string $categorySlug,
        private string $name,
        private ?string $subtitle,
        private int $priceCents,
        private string $currency,
        private ProductImage $thumbnail,
        private array $images,
        private string $shortDescription,
        private string $description,
        private bool $featured,
        private string $status,
        private array $features,
        private ProductTabs $tabs,
        private string $shippingText,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            slug: $data['slug'],
            categorySlug: $data['categorySlug'],
            name: $data['name'],
            subtitle: $data['subtitle'] ?? null,
            priceCents: $data['priceCents'],
            currency: $data['currency'] ?? self::DEFAULT_CURRENCY,
            thumbnail: ProductImage::fromString($data['thumbnail']),
            images: array_map(
                static fn (string $image): ProductImage => ProductImage::fromString($image),
                $data['images'] ?? [],
            ),
            shortDescription: $data['shortDescription'],
            description: $data['description'],
            featured: $data['featured'] ?? false,
            status: $data['status'],
            features: array_map(
                static fn (array $feature): ProductFeature => ProductFeature::fromArray($feature),
                $data['features'] ?? [],
            ),
            tabs: ProductTabs::fromArray($data['tabs'] ?? []),
            shippingText: $data['shippingText'] ?? self::DEFAULT_SHIPPING_TEXT,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function categorySlug(): string
    {
        return $this->categorySlug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function subtitle(): ?string
    {
        return $this->subtitle;
    }

    public function priceCents(): int
    {
        return $this->priceCents;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function formattedPrice(): string
    {
        return sprintf('$%s', number_format($this->priceCents / self::CENTS_PER_UNIT, 2));
    }

    public function thumbnail(): ProductImage
    {
        return $this->thumbnail;
    }

    /**
     * @return ProductImage[]
     */
    public function images(): array
    {
        return $this->images;
    }

    public function shortDescription(): string
    {
        return $this->shortDescription;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function featured(): bool
    {
        return $this->featured;
    }

    public function status(): string
    {
        return $this->status;
    }

    /**
     * @return ProductFeature[]
     */
    public function features(): array
    {
        return $this->features;
    }

    public function tabs(): ProductTabs
    {
        return $this->tabs;
    }

    public function shippingText(): string
    {
        return $this->shippingText;
    }

    public function isInStock(): bool
    {
        return $this->status === self::STATUS_IN_STOCK;
    }
}
