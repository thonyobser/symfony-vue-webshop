import { ref } from 'vue';
import type { CartItem, CartNotification, CartProduct, CartResponse } from './types';

const CART_SHOW_ENDPOINT = '/checkout/cart/api';
const CART_ADD_ENDPOINT = '/checkout/cart/api/items';
const CART_UPDATED_EVENT = 'aperture:cart-updated';
const NOTIFICATION_VISIBLE_MILLISECONDS = 3500;
const MIN_QUANTITY = 1;

export const cartCount = ref(0);
export const cartItems = ref<CartItem[]>([]);
export const cartTotalCents = ref(0);
export const notifications = ref<CartNotification[]>([]);

let nextNotificationId = 1;
let initialized = false;

function syncCart(payload: CartResponse): void {
    cartItems.value = payload.items;
    cartCount.value = payload.count;
    cartTotalCents.value = payload.totalCents;
    window.dispatchEvent(new CustomEvent(CART_UPDATED_EVENT, { detail: { count: cartCount.value } }));
}

function normalizeQuantity(quantity: number): number {
    return Number.isNaN(quantity) ? MIN_QUANTITY : Math.max(MIN_QUANTITY, Math.floor(quantity));
}

function showNotification(message: string): void {
    const id = nextNotificationId;

    nextNotificationId += 1;
    notifications.value = [...notifications.value, { id, message }];

    window.setTimeout(() => {
        dismissNotification(id);
    }, NOTIFICATION_VISIBLE_MILLISECONDS);
}

function requestCart(url: string, options: RequestInit = {}): Promise<CartResponse> {
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
            throw new Error('Could not update cart.');
        }

        return response.json();
    });
}

export function loadCart(): Promise<void> {
    return requestCart(CART_SHOW_ENDPOINT).then(syncCart).catch(() => {
        cartItems.value = [];
        cartCount.value = 0;
        cartTotalCents.value = 0;
        showNotification('Could not load your cart.');
    });
}

export function dismissNotification(id: number): void {
    notifications.value = notifications.value.filter((notification) => notification.id !== id);
}

export function addProduct(product: CartProduct, quantity = MIN_QUANTITY): Promise<void> {
    const normalizedQuantity = normalizeQuantity(quantity);

    return requestCart(CART_ADD_ENDPOINT, {
        method: 'POST',
        body: JSON.stringify({
            productId: product.id,
            quantity: normalizedQuantity,
        }),
    }).then((payload) => {
        syncCart(payload);
        showNotification(`${normalizedQuantity === 1 ? '1 product' : `${normalizedQuantity} products`} added to your cart: ${product.name}.`);
    }).catch(() => {
        showNotification('Could not update your cart. Please try again.');
    });
}

export function updateProductQuantity(productId: string, quantity: number): Promise<void> {
    return requestCart(`${CART_ADD_ENDPOINT}/${encodeURIComponent(productId)}`, {
        method: 'PATCH',
        body: JSON.stringify({
            quantity: normalizeQuantity(quantity),
        }),
    }).then(syncCart).catch(() => {
        showNotification('Could not update your cart. Please try again.');
    });
}

export function removeProduct(productId: string): Promise<void> {
    return requestCart(`${CART_ADD_ENDPOINT}/${encodeURIComponent(productId)}`, {
        method: 'DELETE',
    }).then(syncCart).catch(() => {
        showNotification('Could not update your cart. Please try again.');
    });
}

export function clearCart(): Promise<void> {
    return requestCart(CART_SHOW_ENDPOINT, {
        method: 'DELETE',
    }).then(syncCart).catch(() => {
        showNotification('Could not clear your cart. Please try again.');
    });
}

export function initializeCart(): void {
    if (initialized) {
        return;
    }

    initialized = true;
    void loadCart();
}
