<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopUi } from '@/composables/shop/useShopUi';
import { useShopWishlist } from '@/composables/shop/useShopWishlist';
import { formatTaka } from '@/lib/shop/currency';
import { productShowUrl } from '@/lib/shop/product';
import type { ShopProduct } from '@/types/shop';

const { product } = defineProps<{
    product: ShopProduct;
}>();

const { addToCart, buyNow } = useShopCart();
const { showToast } = useShopUi();
const { toggleWish, isWishlisted: checkWishlisted } = useShopWishlist();

const isWishlisted = computed(() =>
    product.id ? checkWishlisted(product.id) : false,
);

const fullStars = Math.floor(product.rating);

function handleToggleWish(): void {
    toggleWish(product, showToast);
}

function handleAddToCart(): void {
    if (!product.id) return;
    addToCart(product.id, 1);
    showToast('Added to cart');
}

function handleBuyNow(): void {
    if (!product.id) return;
    buyNow(product.id, 1);
}
</script>

<template>
    <article
        class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-shop-primary-600 hover:shadow-xl"
    >
        <div class="relative aspect-square overflow-hidden bg-gray-100">
            <Link
                :href="productShowUrl(product)"
                :aria-label="`View ${product.name}`"
                class="block h-full w-full"
            >
                <img
                    :src="product.img"
                    :alt="product.name"
                    loading="lazy"
                    class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110"
                    :class="{ grayscale: !product.inStock }"
                />
                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"
                />
            </Link>

            <div
                v-if="!product.inStock"
                class="pointer-events-none absolute inset-0 flex items-center justify-center bg-white/30"
            >
                <span
                    class="rounded-md bg-gray-900/85 px-4 py-1.5 text-xs font-semibold tracking-wide text-white uppercase shadow-md"
                    >Stock Out</span
                >
            </div>

            <div
                class="absolute top-2 left-2 flex flex-col items-start gap-1.5"
            >
                <span
                    v-if="product.inStock"
                    class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 shadow-sm"
                    >In Stock</span
                >
                <span
                    v-if="product.tag"
                    class="rounded-full bg-shop-accent-500 px-2.5 py-1 text-xs font-semibold text-white shadow-sm"
                    >{{ product.tag }}</span
                >
            </div>

            <button
                type="button"
                aria-label="Add to wishlist"
                :aria-pressed="isWishlisted"
                class="wish-btn absolute top-2 right-2 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-gray-700 shadow-sm transition duration-200 hover:scale-110 hover:bg-white hover:text-red-600 active:scale-95 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none sm:translate-y-1 sm:opacity-0 sm:group-hover:translate-y-0 sm:group-hover:opacity-100"
                :class="{ 'text-red-600': isWishlisted }"
                @click="handleToggleWish"
            >
                <svg
                    class="h-5 w-5"
                    :fill="isWishlisted ? 'currentColor' : 'none'"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                    />
                </svg>
            </button>
        </div>

        <div class="flex flex-1 flex-col p-3 md:p-4">
            <Link :href="productShowUrl(product)" class="block">
                <h3
                    class="line-clamp-2 text-sm font-medium text-gray-900 transition-colors duration-200 group-hover:text-shop-primary-600 md:text-base"
                >
                    {{ product.name }}
                </h3>
            </Link>

            <div class="mt-1.5 flex items-center gap-1">
                <div class="flex">
                    <svg
                        v-for="star in 5"
                        :key="star"
                        class="h-3.5 w-3.5"
                        :class="
                            star <= fullStars
                                ? 'text-shop-accent-500'
                                : 'text-gray-300'
                        "
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"
                        />
                    </svg>
                </div>
                <span class="text-xs text-gray-400"
                    >({{ product.reviews }})</span
                >
            </div>

            <div class="mt-2 flex items-center gap-2">
                <span
                    class="text-base font-semibold text-shop-primary-600 md:text-lg"
                    >{{ formatTaka(product.price) }}</span
                >
                <span
                    v-if="product.oldPrice"
                    class="text-xs text-gray-400 line-through"
                    >{{ formatTaka(product.oldPrice) }}</span
                >
            </div>

            <div class="mt-auto">
                <div class="mt-3 flex flex-col gap-2">
                    <button
                        v-if="product.inStock"
                        type="button"
                        class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-shop-primary-600 px-3 py-2.5 text-sm font-medium text-shop-primary-600 transition hover:bg-shop-primary-50 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @click="handleAddToCart"
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
                        class="inline-flex w-full cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-400"
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
                        v-if="product.inStock"
                        type="button"
                        class="w-full rounded-lg bg-shop-primary-600 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @click="handleBuyNow"
                    >
                        Buy Now
                    </button>
                    <button
                        v-else
                        type="button"
                        disabled
                        aria-disabled="true"
                        class="w-full cursor-not-allowed rounded-lg bg-gray-200 px-3 py-2.5 text-sm font-semibold text-gray-400"
                    >
                        Notify Me
                    </button>
                </div>
            </div>
        </div>
    </article>
</template>
