<template>
    <button type="button" @click="addProduct(product)">
        <slot />
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { addProduct } from '../cart/store';

const props = defineProps<{
    productId: string;
    productName: string;
    priceCents: number | string;
    image?: string;
    url?: string;
}>();

const product = computed(() => ({
    id: props.productId,
    name: props.productName,
    priceCents: Number(props.priceCents),
    image: props.image ?? '',
    url: props.url ?? '',
}));
</script>
