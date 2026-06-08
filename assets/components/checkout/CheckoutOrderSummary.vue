<template>
    <aside class="h-fit bg-white p-5 shadow-card md:p-6">
        <h2 class="text-lg font-black text-aperture-dark">Summary</h2>

        <dl class="mt-5 space-y-3 text-sm">
            <div class="flex justify-between gap-4">
                <dt class="text-aperture-muted">Subtotal</dt>
                <dd class="font-extrabold text-aperture-ink">{{ money(subtotalCents) }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-aperture-muted">Tax</dt>
                <dd class="font-extrabold text-aperture-ink">{{ money(taxCents) }}</dd>
            </div>
            <div class="flex justify-between gap-4 border-t border-aperture-line pt-4 text-base">
                <dt class="font-black text-aperture-dark">Total</dt>
                <dd class="font-black text-aperture-dark">{{ money(totalCents) }}</dd>
            </div>
        </dl>

        <div class="mt-6 border border-aperture-line bg-aperture-panel p-4">
            <p class="text-xs font-extrabold uppercase tracking-[0.12em] text-aperture-muted">Idempotency key</p>
            <code class="mt-2 block break-all text-xs text-aperture-ink">{{ idempotencyKey || 'Generating...' }}</code>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { cartTotalCents } from '../../cart/store';
import { money } from './money';

defineProps<{
    idempotencyKey: string;
}>();

const TAX_RATE = 0.19;
const subtotalCents = computed(() => cartTotalCents.value);
const taxCents = computed(() => Math.round(subtotalCents.value * TAX_RATE));
const totalCents = computed(() => subtotalCents.value + taxCents.value);
</script>
