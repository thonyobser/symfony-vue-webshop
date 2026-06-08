<template>
    <section class="ap-container py-12 md:py-16">
        <div class="flex flex-col gap-4 border-b border-aperture-line pb-8 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-aperture-blue">Wishlist</p>
                <h1 class="mt-3 font-display text-4xl font-black leading-none text-aperture-dark md:text-5xl">
                    Saved products
                </h1>
            </div>

            <button
                v-if="wishlistItems.length > 0"
                type="button"
                class="ap-button ap-button-secondary ap-button-sm"
                @click="clearWishlist"
            >
                Clear wishlist
            </button>
        </div>

        <div v-if="wishlistItems.length === 0" class="py-16">
            <p class="max-w-xl text-base leading-7 text-aperture-muted">
                Your wishlist is empty. Save products from the catalog and they will appear here.
            </p>
        </div>

        <div v-else class="grid gap-6 py-10">
            <article
                v-for="item in wishlistItems"
                :key="item.id"
                class="grid gap-5 border border-aperture-line rounded-md bg-white p-4 sm:grid-cols-[8rem_1fr] md:p-5"
            >
                <a :href="item.url" class="flex aspect-square items-center justify-center p-4">
                    <img :src="item.image" :alt="item.name" class="h-full w-full object-contain">
                </a>

                <div class="flex min-w-0 flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="min-w-0">
                        <h2 class="text-lg font-extrabold text-aperture-dark">
                            <a :href="item.url" class="transition hover:text-aperture-blue">{{ item.name }}</a>
                        </h2>
                        <p class="mt-2 text-sm font-extrabold text-aperture-dark">{{ formatMoney(item.priceCents) }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a :href="item.url" class="ap-button ap-button-primary ap-button-sm">
                            View product
                        </a>
                        <button
                            type="button"
                            class="ap-button ap-button-secondary ap-button-sm"
                            @click="removeWishlistProduct(item.id)"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</template>

<script setup lang="ts">
import { clearWishlist, removeWishlistProduct, wishlistItems } from '../wishlist/store';

function formatMoney(cents: number): string {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(cents / 100);
}
</script>
