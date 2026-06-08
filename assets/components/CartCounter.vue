<template>
    <span>Cart ({{ cartCount }})</span>
</template>

<script setup lang="ts">
import { cartCount } from '../cart/store';
</script>
