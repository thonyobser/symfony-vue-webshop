<template>
    <div>
        <div class="flex min-h-[34rem] items-center justify-center border border-aperture-line bg-gradient-to-b from-white to-aperture-panel p-8 md:min-h-[42rem] md:p-12">
            <img
                :src="activeImage.src"
                :alt="productName"
                class="h-full max-h-[36rem] w-full object-contain"
                loading="eager"
            >
        </div>

        <div class="mt-6 flex flex-wrap gap-4" aria-label="Product images">
            <button
                v-for="(image, index) in images"
                :key="`${image.src}-${index}`"
                type="button"
                class="flex h-24 w-24 items-center justify-center border bg-white p-2 transition hover:border-aperture-blue"
                :class="isActive(index) ? 'border-aperture-blue ring-1 ring-aperture-blue' : 'border-aperture-line'"
                :aria-label="`View ${productName} image ${index + 1}`"
                :aria-current="isActive(index) ? 'true' : 'false'"
                @click="setActiveIndex(index)"
            >
                <img
                    :src="image.src"
                    alt=""
                    class="h-full w-full object-contain"
                    loading="lazy"
                >
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';

type GalleryImage = {
    src: string;
};

const props = defineProps<{
    images: GalleryImage[];
    productName: string;
}>();

const activeIndex = ref(0);
const activeImage = computed(() => props.images[activeIndex.value] ?? props.images[0] ?? { src: '' });

function normalizeIndex(index: number | string): number {
    return typeof index === 'number' ? index : Number(index);
}

function isActive(index: number | string): boolean {
    return normalizeIndex(index) === activeIndex.value;
}

function setActiveIndex(index: number | string): void {
    activeIndex.value = normalizeIndex(index);
}
</script>
