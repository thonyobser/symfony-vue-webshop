<template>
    <form action="#" method="post" @submit.prevent="submit">
        <slot />
    </form>
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

function submit(event: Event): void {
    const form = event.currentTarget instanceof HTMLElement ? event.currentTarget : null;
    const input = form?.querySelector<HTMLInputElement>('[name="quantity"]');
    const quantity = Number(input?.value ?? 1);

    addProduct(product.value, Number.isNaN(quantity) ? 1 : quantity);
}
</script>
