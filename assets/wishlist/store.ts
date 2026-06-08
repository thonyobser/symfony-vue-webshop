import { ref } from 'vue';
import type { WishlistNotification, WishlistProduct, WishlistResponse } from './types';

const WISHLIST_SHOW_ENDPOINT = '/wishlist/api';
const WISHLIST_ITEMS_ENDPOINT = '/wishlist/api/items';
const WISHLIST_UPDATED_EVENT = 'aperture:wishlist-updated';
const NOTIFICATION_VISIBLE_MILLISECONDS = 3500;

export const wishlistCount = ref(0);
export const wishlistItems = ref<WishlistProduct[]>([]);
export const wishlistProductIds = ref<string[]>([]);
export const wishlistNotifications = ref<WishlistNotification[]>([]);

let nextNotificationId = 1;
let initialized = false;

function syncWishlist(payload: WishlistResponse): void {
    wishlistItems.value = payload.items;
    wishlistProductIds.value = payload.productIds;
    wishlistCount.value = payload.count;
    window.dispatchEvent(new CustomEvent(WISHLIST_UPDATED_EVENT, { detail: { count: wishlistCount.value } }));
}

function showNotification(message: string): void {
    const id = nextNotificationId;

    nextNotificationId += 1;
    wishlistNotifications.value = [...wishlistNotifications.value, { id, message }];

    window.setTimeout(() => {
        dismissWishlistNotification(id);
    }, NOTIFICATION_VISIBLE_MILLISECONDS);
}

function requestWishlist(url: string, options: RequestInit = {}): Promise<WishlistResponse> {
    return fetch(url, {
        ...options,
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            ...(options.body ? { 'Content-Type': 'application/json' } : {}),
            ...options.headers,
        },
    }).then((response) => {
        if (!response.ok) {
            throw new Error('Could not update wishlist.');
        }

        return response.json();
    });
}

export function loadWishlist(): Promise<void> {
    return requestWishlist(WISHLIST_SHOW_ENDPOINT).then(syncWishlist).catch(() => {
        wishlistItems.value = [];
        wishlistProductIds.value = [];
        wishlistCount.value = 0;
        showNotification('Could not load your wishlist.');
    });
}

export function dismissWishlistNotification(id: number): void {
    wishlistNotifications.value = wishlistNotifications.value.filter((notification) => notification.id !== id);
}

export function isWishlisted(productId: string): boolean {
    return wishlistProductIds.value.includes(productId);
}

export function addWishlistProduct(product: WishlistProduct): Promise<void> {
    return requestWishlist(WISHLIST_ITEMS_ENDPOINT, {
        method: 'POST',
        body: JSON.stringify({
            productId: product.id,
        }),
    }).then((payload) => {
        syncWishlist(payload);
        showNotification(`Added to your wishlist: ${product.name}.`);
    }).catch(() => {
        showNotification('Could not update your wishlist. Please try again.');
    });
}

export function removeWishlistProduct(productId: string): Promise<void> {
    return requestWishlist(`${WISHLIST_ITEMS_ENDPOINT}/${encodeURIComponent(productId)}`, {
        method: 'DELETE',
    }).then((payload) => {
        syncWishlist(payload);
        showNotification('Removed from your wishlist.');
    }).catch(() => {
        showNotification('Could not update your wishlist. Please try again.');
    });
}

export function toggleWishlistProduct(product: WishlistProduct): Promise<void> {
    if (isWishlisted(product.id)) {
        return removeWishlistProduct(product.id);
    }

    return addWishlistProduct(product);
}

export function clearWishlist(): Promise<void> {
    return requestWishlist(WISHLIST_SHOW_ENDPOINT, {
        method: 'DELETE',
    }).then(syncWishlist).catch(() => {
        showNotification('Could not clear your wishlist. Please try again.');
    });
}

export function initializeWishlist(): void {
    if (initialized) {
        return;
    }

    initialized = true;
    void loadWishlist();
}
