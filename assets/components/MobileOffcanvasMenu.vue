<template>
    <button
        type="button"
        class="inline-flex h-12 w-12 items-center justify-center border border-aperture-line bg-white text-aperture-ink transition hover:border-aperture-blue hover:text-aperture-blue lg:hidden"
        aria-label="Open menu"
        :aria-expanded="isOpen ? 'true' : 'false'"
        @click="open"
    >
        <span class="block h-4 w-5 space-y-1" aria-hidden="true">
            <span class="block h-0.5 bg-current"></span>
            <span class="block h-0.5 bg-current"></span>
            <span class="block h-0.5 bg-current"></span>
        </span>
    </button>

    <Teleport to="body">
        <div v-if="isOpen" class="fixed inset-0 z-50 lg:hidden">
            <button
                type="button"
                class="absolute inset-0 h-full w-full bg-aperture-dark/60"
                aria-label="Close menu"
                @click="close"
            ></button>

            <aside class="absolute right-0 top-0 flex h-full w-[min(24rem,100vw)] flex-col bg-white shadow-2xl">
                <header class="flex h-20 items-center justify-between border-b border-aperture-line px-5">
                    <a :href="homeHref" class="text-sm font-black uppercase tracking-[0.14em] text-aperture-dark" @click="close">
                        Aperture
                    </a>
                    <button
                        type="button"
                        class="flex h-11 w-11 items-center justify-center border border-aperture-line text-xl leading-none text-aperture-ink transition hover:border-aperture-blue hover:text-aperture-blue"
                        aria-label="Close menu"
                        @click="close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>

                <div class="flex-1 overflow-y-auto px-5 py-6">
                    <form :action="searchAction" method="get" class="flex h-12 items-center gap-3 border border-aperture-line bg-aperture-panel px-4">
                        <label for="mobile-site-search" class="sr-only">Search Aperture Science</label>
                        <input
                            id="mobile-site-search"
                            name="q"
                            type="search"
                            placeholder="Search Aperture Science..."
                            class="min-w-0 flex-1 border-0 bg-transparent text-sm outline-none placeholder:text-aperture-muted/70"
                        >
                        <button type="submit" class="text-xs font-extrabold uppercase tracking-[0.12em] text-aperture-blue">
                            Search
                        </button>
                    </form>

                    <nav class="mt-8" aria-label="Mobile navigation">
                        <a
                            v-for="item in navItems"
                            :key="item.href + item.label"
                            :href="item.href"
                            class="flex min-h-14 items-center justify-between border-b border-aperture-line py-4 text-sm font-extrabold uppercase tracking-[0.12em] transition hover:text-aperture-blue"
                            :class="item.active ? 'text-aperture-blue' : 'text-aperture-ink'"
                            :aria-current="item.active ? 'page' : undefined"
                            @click="close"
                        >
                            <span>{{ item.label }}</span>
                            <span aria-hidden="true" class="text-lg">&gt;</span>
                        </a>
                    </nav>
                </div>

                <footer class="border-t border-aperture-line p-5">
                    <div class="grid gap-3">
                        <a
                            :href="cartHref"
                            class="flex h-12 items-center justify-center border border-aperture-dark bg-aperture-dark px-4 text-xs font-extrabold uppercase tracking-[0.12em] text-white transition hover:bg-black"
                            @click="close"
                        >
                            <CartCounter />
                        </a>
                        <a
                            :href="wishlistHref"
                            class="flex h-12 items-center justify-center border border-aperture-line bg-white px-4 text-xs font-extrabold uppercase tracking-[0.12em] text-aperture-blue transition hover:border-aperture-blue hover:bg-aperture-panel"
                            @click="close"
                        >
                            <WishlistCounter />
                        </a>
                        <a
                            :href="loginHref"
                            class="flex h-12 items-center justify-center border border-aperture-line bg-white px-4 text-xs font-extrabold uppercase tracking-[0.12em] text-aperture-blue transition hover:border-aperture-blue hover:bg-aperture-panel"
                            @click="close"
                        >
                            Hello Customer #1337
                        </a>
                    </div>
                </footer>
            </aside>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { onBeforeUnmount, ref, watch } from 'vue';
import CartCounter from './CartCounter.vue';
import WishlistCounter from './WishlistCounter.vue';

type NavItem = {
    label: string;
    href: string;
    active?: boolean;
};

defineProps<{
    navItems: NavItem[];
    homeHref: string;
    loginHref: string;
    cartHref: string;
    wishlistHref: string;
    searchAction: string;
}>();

const isOpen = ref(false);

function open(): void {
    isOpen.value = true;
}

function close(): void {
    isOpen.value = false;
}

function closeOnEscape(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        close();
    }
}

watch(isOpen, (openState) => {
    document.body.classList.toggle('overflow-hidden', openState);

    if (openState) {
        window.addEventListener('keydown', closeOnEscape);
    } else {
        window.removeEventListener('keydown', closeOnEscape);
    }
});

onBeforeUnmount(() => {
    document.body.classList.remove('overflow-hidden');
    window.removeEventListener('keydown', closeOnEscape);
});
</script>
