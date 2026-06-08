<template>
    <div class="inline-flex h-14 w-full items-center justify-between border border-aperture-line bg-white sm:w-36">
        <button
            type="button"
            class="flex h-full w-12 items-center justify-center text-aperture-muted transition hover:text-aperture-blue"
            aria-label="Decrease quantity"
            @click="setQuantity(quantity - 1)"
        >
            -
        </button>

        <label :for="inputId" class="sr-only">Quantity</label>
        <input
            :id="inputId"
            v-model="displayQuantity"
            :name="name"
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            min="1"
            class="h-full w-12 border-0 bg-transparent p-0 text-center text-sm font-extrabold text-aperture-dark outline-none focus:ring-0"
            @change="setQuantity(Number(displayQuantity))"
        >

        <button
            type="button"
            class="flex h-full w-12 items-center justify-center text-aperture-muted transition hover:text-aperture-blue"
            aria-label="Increase quantity"
            @click="setQuantity(quantity + 1)"
        >
            +
        </button>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';

const props = withDefaults(defineProps<{
    initialValue?: number | string;
    name?: string;
    inputId?: string;
}>(), {
    initialValue: 1,
    name: 'quantity',
    inputId: 'product-quantity',
});

const quantity = ref(normalizeQuantity(Number(props.initialValue)));
const displayQuantity = computed({
    get: () => String(quantity.value),
    set: (value: string) => setQuantity(Number(value)),
});

function normalizeQuantity(value: number): number {
    return Number.isNaN(value) ? 1 : Math.max(1, Math.floor(value));
}

function setQuantity(value: number): void {
    quantity.value = normalizeQuantity(value);
}
</script>
