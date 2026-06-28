<script setup lang="ts">
import { ref } from 'vue';
import { useShopCarousel } from '@/composables/shop/useShopCarousel';
import type { ShopCarouselSlide } from '@/types/shop';

const { slides } = defineProps<{
    slides: ShopCarouselSlide[];
}>();

const {
    current,
    goTo,
    next,
    prev,
    startAuto,
    stopAuto,
    handleTouchStart,
    handleTouchEnd,
} = useShopCarousel(slides.length);

const touchStartX = ref(0);

function onTouchStart(event: TouchEvent): void {
    touchStartX.value = handleTouchStart(event);
}

function onTouchEnd(event: TouchEvent): void {
    handleTouchEnd(event, touchStartX.value);
}
</script>

<template>
    <section
        v-if="slides.length > 0"
        class="relative"
        aria-roledescription="carousel"
        aria-label="Featured promotions"
    >
        <div
            class="relative h-[40vh] overflow-hidden sm:h-[55vh] lg:h-[70vh]"
            @mouseenter="stopAuto"
            @mouseleave="startAuto"
            @touchstart.passive="onTouchStart"
            @touchend.passive="onTouchEnd"
        >
            <div
                v-for="(slide, index) in slides"
                :key="index"
                class="shop-slide absolute inset-0"
                :class="{ active: current === index }"
                role="group"
                aria-roledescription="slide"
                :aria-label="`${index + 1} of ${slides.length}`"
            >
                <img
                    :src="slide.src"
                    :alt="slide.alt"
                    class="h-full w-full object-cover"
                />
            </div>

            <button
                type="button"
                aria-label="Previous slide"
                class="absolute top-1/2 left-4 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-gray-900 shadow transition hover:bg-white focus:ring-2 focus:ring-shop-primary-600 focus:outline-none md:flex"
                @click="
                    prev();
                    startAuto();
                "
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>
            <button
                type="button"
                aria-label="Next slide"
                class="absolute top-1/2 right-4 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-gray-900 shadow transition hover:bg-white focus:ring-2 focus:ring-shop-primary-600 focus:outline-none md:flex"
                @click="
                    next();
                    startAuto();
                "
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>

            <div
                class="absolute bottom-4 left-1/2 flex -translate-x-1/2 items-center gap-2"
            >
                <button
                    v-for="(_, index) in slides"
                    :key="index"
                    type="button"
                    :aria-label="`Go to slide ${index + 1}`"
                    class="h-2.5 w-2.5 rounded-full transition"
                    :class="
                        current === index ? 'bg-white' : 'bg-white/50'
                    "
                    :aria-current="current === index ? 'true' : 'false'"
                    @click="
                        goTo(index);
                        startAuto();
                    "
                />
            </div>
        </div>
    </section>
</template>
