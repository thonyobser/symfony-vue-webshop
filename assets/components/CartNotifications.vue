<template>
    <div
        class="fixed right-4 top-4 z-50 flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3"
        role="status"
        aria-live="polite"
        aria-atomic="true"
    >
        <div
            v-for="notification in notifications"
            :key="notification.id"
            class="flex items-start justify-between gap-4 border border-aperture-line rounded-md bg-white px-4 py-3 text-sm font-bold text-aperture-ink shadow-card"
        >
            <span>{{ notification.message }}</span>
            <button
                type="button"
                class="shrink-0 text-aperture-muted transition hover:text-aperture-blue"
                aria-label="Dismiss notification"
                @click="dismissNotification(notification.id)"
            >
                Close
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { dismissNotification, notifications } from '../cart/store';
</script>
