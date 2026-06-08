<template>
    <form class="space-y-5" @submit.prevent="$emit('next')">
        <p class="text-sm leading-7 text-aperture-muted">Shipping is a placeholder for now. The idempotency key below is the value a future API request would send to make retries safe.</p>
        <input type="hidden" name="idempotencyKey" :value="idempotencyKey">

        <div class="grid gap-4 sm:grid-cols-2">
            <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="First name">
            <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="Last name">
            <input class="h-12 border border-aperture-line px-4 text-sm sm:col-span-2" placeholder="Address">
            <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="Postal code">
            <input class="h-12 border border-aperture-line px-4 text-sm" placeholder="City">
        </div>

        <div class="flex flex-wrap justify-between gap-3 pt-4">
            <button type="button" class="ap-button ap-button-ghost ap-button-md" @click="$emit('back')">Back</button>
            <button type="submit" class="ap-button ap-button-primary ap-button-md">Continue to payment</button>
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
