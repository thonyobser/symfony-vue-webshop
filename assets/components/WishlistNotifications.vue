<template>
    <div class="pointer-events-none fixed right-4 top-4 z-50 grid w-[min(24rem,calc(100vw-2rem))] gap-3">
        <div
            v-for="notification in wishlistNotifications"
            :key="notification.id"
            class="pointer-events-auto flex items-start justify-between gap-4 border border-aperture-line bg-white p-4 text-sm font-semibold text-aperture-ink shadow-xl"
        >
            <span>{{ notification.message }}</span>
            <button
                type="button"
                class="text-aperture-muted transition hover:text-aperture-blue"
                aria-label="Dismiss notification"
                @click="dismissWishlistNotification(notification.id)"
            >
                &times;
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { dismissWishlistNotification, wishlistNotifications } from '../wishlist/store';
</script>
