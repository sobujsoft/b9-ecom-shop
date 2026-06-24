<script setup lang="ts">
import { ref } from 'vue';
import type { ShopProductImage } from '@/types/shop';

const { images, alt } = defineProps<{
    images: ShopProductImage[];
    alt: string;
}>();

const activeIndex = ref(0);

function selectImage(index: number): void {
    activeIndex.value = index;
}
</script>

<template>
    <div>
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-gray-100">
            <img
                :src="images[activeIndex].full"
                :alt="alt"
                class="aspect-square w-full object-cover"
            />
        </div>

        <div class="no-scrollbar mt-4 flex gap-3 overflow-x-auto">
            <button
                v-for="(image, index) in images"
                :key="index"
                type="button"
                class="h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                :class="
                    activeIndex === index
                        ? 'border-shop-primary-600'
                        : 'border-transparent hover:border-gray-300'
                "
                :aria-label="`View image ${index + 1}`"
                @click="selectImage(index)"
            >
                <img
                    :src="image.thumb"
                    alt=""
                    class="h-full w-full object-cover"
                />
            </button>
        </div>
    </div>
</template>
