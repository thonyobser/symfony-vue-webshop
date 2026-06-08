<template>
    <section ref="root">
        <slot />
    </section>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

const ACTIVE_TAB_CLASS = 'border-aperture-blue text-aperture-blue';
const INACTIVE_TAB_CLASS = 'border-transparent text-aperture-dark hover:border-aperture-line hover:text-aperture-blue';
const root = ref<HTMLElement | null>(null);

function toggleClasses(element: Element, classList: string, force: boolean): void {
    classList.split(' ').forEach((className) => element.classList.toggle(className, force));
}

onMounted(() => {
    if (!root.value) {
        return;
    }

    const tabs = Array.from(root.value.querySelectorAll<HTMLAnchorElement>('[data-product-tab]'));
    const panels = Array.from(root.value.querySelectorAll<HTMLElement>('[data-product-tab-panel]'));

    tabs.forEach((tab) => {
        tab.addEventListener('click', (event) => {
            event.preventDefault();

            const targetPanelId = tab.dataset.productTabTarget;

            tabs.forEach((item) => {
                const isActive = item === tab;

                item.setAttribute('aria-current', isActive ? 'page' : 'false');
                toggleClasses(item, ACTIVE_TAB_CLASS, isActive);
                toggleClasses(item, INACTIVE_TAB_CLASS, !isActive);
            });

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.id !== targetPanelId);
            });
        });
    });
});
</script>
