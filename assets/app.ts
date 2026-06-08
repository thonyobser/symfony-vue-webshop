import './styles/app.css';
import { createApp } from 'vue';
import AddToCartButton from './components/AddToCartButton.vue';
import AddToCartForm from './components/AddToCartForm.vue';
import CartCounter from './components/CartCounter.vue';
import CartNotifications from './components/CartNotifications.vue';
import CheckoutApp from './components/checkout/CheckoutApp.vue';
import HeroBanner from './components/HeroBanner.vue';
import ProductGallery from './components/ProductGallery.vue';
import ProductTabs from './components/ProductTabs.vue';
import QuantityPicker from './components/QuantityPicker.vue';
import { initializeCart } from './cart/store';

const root = document.querySelector('[data-vue-app]');

if (root) {
    const app = createApp({});

    app.component('add-to-cart-button', AddToCartButton);
    app.component('add-to-cart-form', AddToCartForm);
    app.component('cart-counter', CartCounter);
    app.component('cart-notifications', CartNotifications);
    app.component('checkout-app', CheckoutApp);
    app.component('hero-banner', HeroBanner);
    app.component('product-gallery', ProductGallery);
    app.component('product-tabs', ProductTabs);
    app.component('quantity-picker', QuantityPicker);
    app.mount(root);
}

initializeCart();
