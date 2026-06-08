<template>
    <div>
        <div v-if="cartItems.length === 0" class="border border-dashed border-aperture-line p-8 text-center">
            <p class="text-lg font-extrabold text-aperture-dark">Your cart is empty.</p>
            <p class="mt-2 text-sm text-aperture-muted">Add products before starting checkout.</p>
        </div>

        <div v-else class="space-y-4">
            <article
                v-for="item in cartItems"
                :key="item.id"
                class="grid gap-4 border rounded-md border-aperture-line p-4 sm:grid-cols-[6rem_minmax(0,1fr)_9rem_auto]"
            >
                <a :href="item.url" class="flex h-24 w-24 items-center justify-center p-2">
                    <img :src="item.image" :alt="item.name" class="h-full w-full object-contain">
                </a>

                <div class="min-w-0">
                    <a :href="item.url" class="text-base font-extrabold text-aperture-ink hover:text-aperture-blue">{{ item.name }}</a>
                    <p class="mt-2 text-sm font-bold text-aperture-muted">{{ money(item.priceCents) }} each</p>
                </div>

                <div>
                    <label :for="`quantity-${item.id}`" class="sr-only">Quantity for {{ item.name }}</label>
                    <input
                        :id="`quantity-${item.id}`"
                        type="number"
                        min="1"
                        :value="item.quantity"
                        class="h-12 w-full border border-aperture-line px-3 text-center text-sm font-extrabold text-aperture-dark"
                        :disabled="pendingProductId === item.id"
                        @change="changeQuantity(item.id, $event)"
                    >
                </div>

                <div class="flex items-start justify-between gap-4 sm:block sm:text-right">
                    <p class="text-sm font-extrabold text-aperture-dark">{{ money(item.lineTotalCents) }}</p>
                    <button
                        type="button"
                        class="mt-0 text-xs font-extrabold uppercase tracking-[0.12em] text-aperture-muted transition hover:text-aperture-blue sm:mt-4"
                        :disabled="pendingProductId === item.id"
                        @click="removeProduct(item.id)"
                    >
                        Remove
                    </button>
                </div>
            </article>

            <div class="flex justify-end pt-4">
                <button type="button" class="ap-button ap-button-primary ap-button-md" @click="$emit('next')">
                    Continue to shipping
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { cartItems, removeProduct as removeCartProduct, updateProductQuantity } from '../../cart/store';
import { money } from './money';

defineEmits<{
    next: [];
}>();

const pendingProductId = ref<string | null>(null);

function changeQuantity(productId: string, event: Event): void {
    const input = event.target instanceof HTMLInputElement ? event.target : null;
    pendingProductId.value = productId;
    updateProductQuantity(productId, Number(input?.value ?? 1)).finally(() => {
        pendingProductId.value = null;
    });
}

function removeProduct(productId: string): void {
    pendingProductId.value = productId;
    removeCartProduct(productId).finally(() => {
        pendingProductId.value = null;
    });
}
</script>
