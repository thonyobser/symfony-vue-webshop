<template>
    <button type="button" :aria-pressed="active ? 'true' : 'false'" @click="toggle">
        <slot :active="active" />
        <span>{{ active ? removeLabel : addLabel }}</span>
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { isWishlisted, toggleWishlistProduct } from '../wishlist/store';

const props = withDefaults(defineProps<{
    productId: string;
    productName: string;
    priceCents: number | string;
    image?: string;
    url?: string;
    addLabel?: string;
    removeLabel?: string;
}>(), {
    addLabel: 'Add to wishlist',
    removeLabel: 'Remove from wishlist',
});

const product = computed(() => ({
    id: props.productId,
    name: props.productName,
    priceCents: Number(props.priceCents),
    image: props.image ?? '',
    url: props.url ?? '',
}));

const active = computed(() => isWishlisted(props.productId));

function toggle(): void {
    void toggleWishlistProduct(product.value);
}
</script>
