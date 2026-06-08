<template>
    <div class="ap-container">
        <div class="mb-8 flex flex-col gap-5 border-b border-aperture-line pb-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-aperture-blue">Checkout</p>
                <h1 class="mt-3 font-display text-4xl font-black leading-none text-aperture-dark md:text-5xl">
                    {{ activeTitle }}
                </h1>
            </div>

            <nav class="flex flex-wrap gap-2" aria-label="Checkout steps">
                <button
                    v-for="step in steps"
                    :key="step.id"
                    type="button"
                    class="border rounded-md px-4 py-3 text-xs font-extrabold uppercase tracking-[0.12em] transition"
                    :class="step.id === activeStep ? 'border-aperture-blue bg-aperture-blue text-white' : 'border-aperture-line bg-white text-aperture-ink hover:border-aperture-blue'"
                    @click="goTo(step.id)"
                >
                    {{ step.label }}
                </button>
            </nav>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_24rem]">
            <section class="min-w-0 bg-white p-5 shadow-card rounded-md md:p-8">
                <CheckoutCartStep v-if="activeStep === 'cart'" @next="goTo('shipping')" />
                <CheckoutShippingStep v-else-if="activeStep === 'shipping'" :idempotency-key="idempotencyKey" @next="goTo('payment')" @back="goTo('cart')" />
                <CheckoutPaymentStep v-else-if="activeStep === 'payment'" :idempotency-key="idempotencyKey" @next="goTo('summary')" @back="goTo('shipping')" />
                <CheckoutSummaryStep v-else :idempotency-key="idempotencyKey" @back="goTo('payment')" />
            </section>

            <CheckoutOrderSummary :idempotency-key="idempotencyKey" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import CheckoutCartStep from './CheckoutCartStep.vue';
import CheckoutOrderSummary from './CheckoutOrderSummary.vue';
import CheckoutPaymentStep from './CheckoutPaymentStep.vue';
import CheckoutShippingStep from './CheckoutShippingStep.vue';
import CheckoutSummaryStep from './CheckoutSummaryStep.vue';

type CheckoutStep = 'cart' | 'shipping' | 'payment' | 'summary';

const props = defineProps<{
    initialStep: CheckoutStep;
}>();

const steps: Array<{ id: CheckoutStep; label: string; title: string }> = [
    { id: 'cart', label: 'Cart', title: 'Review your cart' },
    { id: 'shipping', label: 'Shipping', title: 'Shipping details' },
    { id: 'payment', label: 'Payment', title: 'Payment method' },
    { id: 'summary', label: 'Summary', title: 'Order summary' },
];

const activeStep = ref<CheckoutStep>(steps.some((step) => step.id === props.initialStep) ? props.initialStep : 'cart');
const idempotencyKey = ref('');
const activeTitle = computed(() => steps.find((step) => step.id === activeStep.value)?.title ?? 'Checkout');

onMounted(() => {
    idempotencyKey.value = existingOrNewIdempotencyKey();
    window.addEventListener('popstate', syncStepFromLocation);
});

onUnmounted(() => {
    window.removeEventListener('popstate', syncStepFromLocation);
});

function goTo(step: CheckoutStep): void {
    activeStep.value = step;
    window.history.pushState({}, '', `/checkout/${step}`);
}

function syncStepFromLocation(): void {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    const step = pathParts[pathParts.length - 1] as CheckoutStep | undefined;

    if (step && steps.some((item) => item.id === step)) {
        activeStep.value = step;
    }
}

function existingOrNewIdempotencyKey(): string {
    const key = window.sessionStorage.getItem('checkout_idempotency_key');

    if (key) {
        return key;
    }

    const newKey = window.crypto?.randomUUID?.() ?? `checkout-${Date.now()}-${Math.random().toString(16).slice(2)}`;
    window.sessionStorage.setItem('checkout_idempotency_key', newKey);

    return newKey;
}
</script>
