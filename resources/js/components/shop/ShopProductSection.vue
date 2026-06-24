<script setup lang="ts">
import ShopProductCard from '@/components/shop/ShopProductCard.vue';
import type { ShopProduct } from '@/types/shop';

const { id, title, description, products, background = 'white' } =
    defineProps<{
        id: string;
        title: string;
        description: string;
        products: ShopProduct[];
        background?: 'white' | 'gray';
    }>();
</script>

<template>
    <section
        :id="id"
        class="py-12 md:py-16 lg:py-24"
        :class="background === 'gray' ? 'bg-gray-100' : 'bg-white'"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 md:text-3xl">
                        {{ title }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 md:text-base">
                        {{ description }}
                    </p>
                </div>
                <a
                    href="#"
                    class="hidden items-center gap-1 text-sm font-medium text-shop-primary-600 hover:text-shop-primary-700 sm:inline-flex"
                >
                    View all
                    <svg
                        class="h-4 w-4"
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
                </a>
            </div>

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
