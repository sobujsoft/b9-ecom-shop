<script setup lang="ts">
import ShopProductCard from '@/components/shop/ShopProductCard.vue';
import type { ShopProduct } from '@/types/shop';

const { products } = defineProps<{
    products: ShopProduct[];
}>();
</script>

<template>
    <section class="bg-white py-12 md:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="mb-8 text-2xl font-bold text-gray-900 md:text-3xl">
                You may also like
            </h2>
            <div
                class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4"
            >
                <ShopProductCard
                    v-for="product in products"
                    :key="product.name"
                    :product="product"
                />
            </div>
        </div>
    </section>
</template>
