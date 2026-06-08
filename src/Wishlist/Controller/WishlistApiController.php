<?php

namespace App\Wishlist\Controller;

use App\Catalog\Repository\ProductRepositoryInterface;
use App\Shared\Infrastructure\Storage\StorageUrlGenerator;
use App\Wishlist\Dto\AddWishlistItemRequestDto;
use App\Wishlist\Dto\WishlistDto;
use App\Wishlist\Dto\WishlistItemDto;
use App\Wishlist\Entity\Wishlist;
use App\Wishlist\Service\WishlistService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/wishlist/api', name: 'api_wishlist_')]
class WishlistApiController extends AbstractController
{
    public function __construct(
        private WishlistService $wishlistService,
        private ProductRepositoryInterface $productRepository,
        private StorageUrlGenerator $storageUrlGenerator,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('', name: 'show', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        return $this->wishlistResponse($this->wishlistService->get($request->getSession()));
    }

    #[Route('/items', name: 'items_add', methods: ['POST'])]
    public function add(#[MapRequestPayload] AddWishlistItemRequestDto $wishlistItem, Request $request): JsonResponse
    {
        if ($wishlistItem->productId() === '') {
            return $this->json(['error' => 'Missing productId.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $wishlist = $this->wishlistService->add($request->getSession(), $wishlistItem->productId());
        } catch (InvalidArgumentException) {
            return $this->json(['error' => 'Product was not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->wishlistResponse($wishlist, JsonResponse::HTTP_CREATED);
    }

    #[Route('/items/{productId}', name: 'items_remove', methods: ['DELETE'])]
    public function remove(string $productId, Request $request): JsonResponse
    {
        return $this->wishlistResponse($this->wishlistService->remove($request->getSession(), $productId));
    }

    #[Route('', name: 'clear', methods: ['DELETE'])]
    public function clear(Request $request): JsonResponse
    {
        return $this->wishlistResponse($this->wishlistService->clear($request->getSession()));
    }

    private function wishlistResponse(Wishlist $wishlist, int $status = JsonResponse::HTTP_OK): JsonResponse
    {
        $items = [];

        foreach ($wishlist->productIds() as $productId) {
            $product = $this->productRepository->findOneById($productId);

            if ($product === null) {
                continue;
            }

            $thumbnail = $product->thumbnail();
            $name = $product->subtitle() === null ? $product->name() : sprintf('%s (%s)', $product->name(), $product->subtitle());

            $items[] = new WishlistItemDto(
                id: $product->id(),
                name: $name,
                priceCents: $product->priceCents(),
                image: $this->storageUrlGenerator->url($thumbnail->url()),
                url: $this->urlGenerator->generate('app_product_detail', [
                    'categorySlug' => $product->categorySlug(),
                    'productSlug' => $product->slug(),
                ]),
            );
        }

        $wishlistDto = new WishlistDto(
            items: $items,
            productIds: $wishlist->productIds(),
            count: $wishlist->count(),
        );

        return $this->json($wishlistDto->toArray(), $status);
    }
}
