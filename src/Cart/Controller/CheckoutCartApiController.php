<?php

namespace App\Cart\Controller;

use App\Cart\Dto\AddCartItemRequestDto;
use App\Cart\Dto\CartDto;
use App\Cart\Dto\CartItemDto;
use App\Cart\Dto\UpdateCartItemRequestDto;
use App\Cart\Entity\CheckoutCart;
use App\Catalog\Repository\ProductRepositoryInterface;
use App\Cart\Service\CheckoutCartService;
use App\Shared\Infrastructure\Storage\StorageUrlGenerator;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/checkout/cart/api', name: 'api_checkout_cart_')]
class CheckoutCartApiController extends AbstractController
{
    public function __construct(
        private readonly CheckoutCartService $cartService,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly StorageUrlGenerator $storageUrlGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('', name: 'show', methods: ['GET'])]
    public function show(Request $request): Response
    {
        return $this->cartResponse($this->cartService->get($request->getSession()));
    }

    #[Route('/items', name: 'items_add', methods: ['POST'])]
    public function add(#[MapRequestPayload] AddCartItemRequestDto $cartItem, Request $request): Response
    {
        if ($cartItem->productId() === '') {
            return $this->json(['error' => 'Missing productId.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $cart = $this->cartService->add($request->getSession(), $cartItem->productId(), $cartItem->quantity());
        } catch (InvalidArgumentException) {
            return $this->json(['error' => 'Product was not found.'], Response::HTTP_NOT_FOUND);
        }

        return $this->cartResponse($cart, Response::HTTP_CREATED);
    }

    #[Route('/items/{productId}', name: 'items_update', methods: ['PATCH', 'PUT'])]
    public function update(string $productId, #[MapRequestPayload] UpdateCartItemRequestDto $cartItem, Request $request): Response
    {
        try {
            $cart = $this->cartService->update($request->getSession(), $productId, $cartItem->quantity());
        } catch (InvalidArgumentException) {
            return $this->json(['error' => 'Product was not found.'], Response::HTTP_NOT_FOUND);
        }

        return $this->cartResponse($cart);
    }

    #[Route('/items/{productId}', name: 'items_remove', methods: ['DELETE'])]
    public function remove(string $productId, Request $request): Response
    {
        return $this->cartResponse($this->cartService->remove($request->getSession(), $productId));
    }

    #[Route('', name: 'clear', methods: ['DELETE'])]
    public function clear(Request $request): Response
    {
        return $this->cartResponse($this->cartService->clear($request->getSession()));
    }

    private function cartResponse(CheckoutCart $cart, int $status = Response::HTTP_OK): Response
    {
        $items = [];

        foreach ($cart->items() as $item) {
            $product = $this->productRepository->findOneById($item->productId());

            if ($product === null) {
                continue;
            }

            $thumbnail = $product->thumbnail();
            $name = $product->subtitle() === null ? $product->name() : sprintf('%s (%s)', $product->name(), $product->subtitle());
            $quantity = $item->quantity();

            $items[] = new CartItemDto(
                id: $product->id(),
                name: $name,
                priceCents: $product->priceCents(),
                lineTotalCents: $product->priceCents() * $quantity,
                image: $this->storageUrlGenerator->url($thumbnail->url()),
                url: $this->urlGenerator->generate('app_product_detail', [
                    'categorySlug' => $product->categorySlug(),
                    'productSlug' => $product->slug(),
                ]),
                quantity: $quantity,
            );
        }

        $cartDto = new CartDto(
            items: $items,
            count: array_sum(array_map(static fn (CartItemDto $item): int => $item->quantity(), $items)),
            totalCents: array_sum(array_map(static fn (CartItemDto $item): int => $item->lineTotalCents(), $items)),
        );

        return $this->json($cartDto->toArray(), $status);
    }
}
