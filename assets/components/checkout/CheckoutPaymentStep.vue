<template>
    <form class="space-y-5" @submit.prevent="$emit('next')">
        <p class="text-sm leading-7 text-aperture-muted">Payment is a placeholder. A real payment request should include this idempotency key in a header such as <code>Idempotency-Key</code>.</p>
        <pre class="overflow-auto border border-aperture-line bg-aperture-panel p-4 text-xs text-aperture-ink">POST /checkout/payment
Idempotency-Key: {{ idempotencyKey }}</pre>

        <div class="grid gap-4">
            <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="Card number">
            <div class="grid gap-4 sm:grid-cols-2">
                <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="MM / YY">
                <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="CVC">
            </div>
        </div>

        <div class="flex flex-wrap justify-between gap-3 pt-4">
            <button type="button" class="ap-button ap-button-ghost ap-button-md" @click="$emit('back')">Back</button>
            <button type="submit" class="ap-button ap-button-primary ap-button-md">Review order</button>
        </div>
    </form>
</template>

<script setup lang="ts">
defineProps<{
    idempotencyKey: string;
}>();

defineEmits<{
    back: [];
    next: [];
}>();
</script>
