<template>
    <div class="space-y-5">
        <p class="text-sm leading-7 text-aperture-muted">Summary is a placeholder until shipping and payment persistence exist. The final order creation request should reuse the same idempotency key.</p>
        <pre class="overflow-auto border border-aperture-line bg-aperture-panel p-4 text-xs text-aperture-ink">POST /checkout/orders
Idempotency-Key: {{ idempotencyKey }}</pre>

        <div class="flex flex-wrap justify-between gap-3 pt-4">
            <button type="button" class="ap-button ap-button-ghost ap-button-md" @click="$emit('back')">Back</button>
            <button type="button" class="ap-button ap-button-dark ap-button-md">Place order</button>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    idempotencyKey: string;
}>();

defineEmits<{
    back: [];
}>();
</script>
