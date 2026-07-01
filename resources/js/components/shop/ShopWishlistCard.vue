<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ShopStarRating from '@/components/shop/ShopStarRating.vue';
import { formatTaka } from '@/lib/shop/currency';
import { productShowUrl } from '@/lib/shop/product';
import type { ShopWishlistItem } from '@/types/shop';

const { item } = defineProps<{
    item: ShopWishlistItem;
}>();

const emit = defineEmits<{
    remove: [productId: number];
    addToCart: [productId: number];
}>();
</script>

<template>
    <article
        class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-shop-primary-600 hover:shadow-xl"
    >
        <div class="relative aspect-square overflow-hidden bg-gray-100">
            <Link
                :href="productShowUrl(item)"
                :aria-label="`View ${item.name}`"
                class="block h-full w-full"
            >
                <img
                    :src="item.img"
                    :alt="item.name"
                    loading="lazy"
                    class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110"
                    :class="{ grayscale: !item.inStock }"
                />
            </Link>
            <div
                v-if="!item.inStock"
                class="pointer-events-none absolute inset-0 flex items-center justify-center bg-white/30"
            >
                <span
                    class="rounded-md bg-gray-900/85 px-4 py-1.5 text-xs font-semibold tracking-wide text-white uppercase shadow-md"
                    >Stock Out</span
                >
            </div>
            <div class="absolute top-2 left-2">
                <span
                    v-if="item.inStock"
                    class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 shadow-sm"
                    >In Stock</span
                >
            </div>
            <button
                type="button"
                :aria-label="`Remove ${item.name} from wishlist`"
                class="absolute top-2 right-2 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-red-600 shadow-sm transition duration-200 hover:scale-110 hover:bg-white active:scale-95 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                @click="emit('remove', item.id)"
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"
                    />
                </svg>
            </button>
        </div>
        <div class="flex flex-1 flex-col p-3 md:p-4">
            <Link :href="productShowUrl(item)" class="block">
                <h3
                    class="line-clamp-2 text-sm font-medium text-gray-900 transition-colors duration-200 group-hover:text-shop-primary-600 md:text-base"
                >
                    {{ item.name }}
                </h3>
            </Link>
            <div class="mt-1.5 flex items-center gap-1">
                <ShopStarRating :rating="item.rating" size="sm" />
                <span class="text-xs text-gray-400">({{ item.reviews }})</span>
            </div>
            <div class="mt-2 flex items-center gap-2">
                <span
                    class="text-base font-semibold text-shop-primary-600 md:text-lg"
                    >{{ formatTaka(item.price) }}</span
                >
                <span
                    v-if="item.oldPrice"
                    class="text-xs text-gray-400 line-through"
                    >{{ formatTaka(item.oldPrice) }}</span
                >
            </div>
            <div class="mt-auto pt-3">
                <button
                    v-if="item.inStock"
                    type="button"
                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-shop-primary-600 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @click="emit('addToCart', item.id)"
                >
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
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    Add to Cart
                </button>
                <button
                    v-else
                    type="button"
                    disabled
                    aria-disabled="true"
                    class="w-full cursor-not-allowed rounded-lg bg-gray-200 px-3 py-2.5 text-sm font-semibold text-gray-400"
                >
                    Stock Out
                </button>
            </div>
        </div>
    </article>
</template>
