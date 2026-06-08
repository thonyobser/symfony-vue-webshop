<template>
    <section class="bg-white ap-container pt-12">
        <a
            v-if="activeSlide"
            :href="activeSlide.href"
            class="block border border-aperture-line rounded-md overflow-hidden"
            :aria-label="activeSlide.title"
        >
            <picture>
                <source :srcset="activeSlide.desktopImage" media="(min-width: 1024px)">
                <source :srcset="activeSlide.tabletImage" media="(min-width: 640px)">
                <img
                    :src="activeSlide.mobileImage"
                    :alt="activeSlide.title"
                    class="w-full object-cover sm:h-[28rem] lg:h-[35rem]"
                    fetchpriority="high"
                >
            </picture>
        </a>

        <div class="flex justify-end py-4">
            <div class="flex items-center border border-aperture-line rounded-md bg-white">
                <button
                    type="button"
                    class="flex h-12 w-12 items-center justify-center border-r border-aperture-line text-aperture-ink transition hover:bg-aperture-panel hover:text-aperture-blue disabled:opacity-40"
                    aria-label="Previous banner"
                    :disabled="slides.length < 2"
                    @click="previous"
                >
                    <span aria-hidden="true" class="block text-xl leading-none">&lt;</span>
                </button>
                <span class="flex h-12 min-w-20 items-center justify-center px-4 text-xs font-extrabold uppercase tracking-[0.14em] text-aperture-dark">
                    {{ activeIndex + 1 }} / {{ slides.length }}
                </span>
                <button
                    type="button"
                    class="flex h-12 w-12 items-center justify-center border-l border-aperture-line text-aperture-ink transition hover:bg-aperture-panel hover:text-aperture-blue disabled:opacity-40"
                    aria-label="Next banner"
                    :disabled="slides.length < 2"
                    @click="next"
                >
                    <span aria-hidden="true" class="block text-xl leading-none">&gt;</span>
                </button>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';

type HeroSlide = {
    title: string;
    href: string;
    desktopImage: string;
    tabletImage: string;
    mobileImage: string;
};

const props = defineProps<{
    slides: HeroSlide[];
}>();

const activeIndex = ref(0);
const activeSlide = computed(() => props.slides[activeIndex.value] ?? null);

function previous(): void {
    if (props.slides.length < 2) {
        return;
    }

    activeIndex.value = activeIndex.value === 0 ? props.slides.length - 1 : activeIndex.value - 1;
}

function next(): void {
    if (props.slides.length < 2) {
        return;
    }

    activeIndex.value = activeIndex.value === props.slides.length - 1 ? 0 : activeIndex.value + 1;
}
</script>
