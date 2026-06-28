<script setup lang="ts">
import { onMounted, ref } from 'vue';
import ShopStarRating from '@/components/shop/ShopStarRating.vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopUi } from '@/composables/shop/useShopUi';
import { useShopWishlist } from '@/composables/shop/useShopWishlist';
import { formatTaka } from '@/lib/shop/currency';
import { savingsPercent } from '@/lib/shop/product';
import type { ShopProductDetail } from '@/types/shop';

const { product } = defineProps<{
    product: ShopProductDetail;
}>();

const quantity = defineModel<number>('quantity', { default: 1 });

const { addToCart, buyNow } = useShopCart();
const { showToast } = useShopUi();
const { toggleWish, isWishlisted: checkWishlisted } = useShopWishlist();

const isWishlisted = ref(false);

onMounted(() => {
    isWishlisted.value = checkWishlisted(product.name);
});

function clampQuantity(): void {
    quantity.value = Math.max(1, Number(quantity.value) || 1);
}

function decrementQuantity(): void {
    quantity.value = Math.max(1, quantity.value - 1);
}

function incrementQuantity(): void {
    quantity.value = quantity.value + 1;
}

function handleQuantityInput(event: Event): void {
    const input = event.target as HTMLInputElement;
    input.value = input.value.replace(/[^0-9]/g, '');
    quantity.value = Number(input.value) || 1;
}

function handleToggleWish(): void {
    isWishlisted.value = toggleWish(
        isWishlisted.value,
        product,
        showToast,
    );
}

function handleAddToCart(): void {
    addToCart(product.id, quantity.value);
    showToast('Added to cart');
}

function handleBuyNow(): void {
    buyNow(product.id, quantity.value);
}

function goToReviews(): void {
    document.getElementById('reviews')?.scrollIntoView({ behavior: 'smooth' });
}
</script>

<template>
    <div>
        <a
            :href="product.categoryHref"
            class="text-sm font-medium text-shop-primary-600 hover:text-shop-primary-700"
            >{{ product.category }}</a
        >
        <h1 class="mt-2 text-2xl font-bold text-gray-900 md:text-3xl">
            {{ product.name }}
        </h1>

        <div class="mt-3 flex flex-wrap items-center gap-3">
            <ShopStarRating :rating="product.rating" />
            <span class="text-sm font-medium text-gray-900">{{
                product.rating
            }}</span>
            <button
                type="button"
                class="text-sm text-gray-500 hover:text-shop-primary-600"
                @click="goToReviews"
            >
                {{ product.reviews }} reviews
            </button>
            <span class="text-gray-300">|</span>
            <span
                v-if="product.inStock"
                class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700"
                >In Stock</span
            >
        </div>

        <div class="mt-5 flex flex-wrap items-end gap-3">
            <span class="text-3xl font-bold text-shop-primary-600">{{
                formatTaka(product.price)
            }}</span>
            <span
                v-if="product.oldPrice"
                class="text-lg text-gray-400 line-through"
                >{{ formatTaka(product.oldPrice) }}</span
            >
            <span
                v-if="product.oldPrice"
                class="rounded-full bg-shop-accent-500 px-2.5 py-1 text-xs font-semibold text-white"
            >
                Save {{ savingsPercent(product.price, product.oldPrice) }}%
            </span>
        </div>

        <p class="mt-5 max-w-prose text-sm leading-relaxed text-gray-600 md:text-base">
            {{ product.summary }}
        </p>

        <hr class="my-6 border-gray-200" />

        <div class="flex flex-wrap items-center gap-4">
            <div>
                <span class="mb-1.5 block text-sm font-medium text-gray-700"
                    >Quantity</span
                >
                <div
                    class="inline-flex items-center rounded-lg border border-gray-300"
                >
                    <button
                        type="button"
                        aria-label="Decrease quantity"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @click="decrementQuantity"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M20 12H4"
                            />
                        </svg>
                    </button>
                    <input
                        :value="quantity"
                        type="text"
                        inputmode="numeric"
                        aria-label="Quantity"
                        class="h-11 w-12 border-x border-gray-300 text-center text-sm font-medium text-gray-900 focus:outline-none"
                        @input="handleQuantityInput"
                        @blur="clampQuantity"
                    />
                    <button
                        type="button"
                        aria-label="Increase quantity"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @click="incrementQuantity"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row">
            <button
                type="button"
                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-shop-primary-600 px-5 py-3 text-sm font-semibold text-shop-primary-600 transition hover:bg-shop-primary-50 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                @click="handleAddToCart"
            >
                <svg
                    class="h-5 w-5"
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
                type="button"
                class="inline-flex flex-1 items-center justify-center rounded-lg bg-shop-primary-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-shop-primary-700 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                @click="handleBuyNow"
            >
                Buy Now
            </button>
            <button
                type="button"
                aria-label="Add to wishlist"
                :aria-pressed="isWishlisted"
                class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-lg border border-gray-300 text-gray-600 transition hover:border-red-300 hover:text-red-600 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                :class="{
                    'border-red-600 text-red-600': isWishlisted,
                }"
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

        <div
            class="mt-6 space-y-3 rounded-xl border border-gray-200 bg-gray-50 p-4"
        >
            <div class="flex items-start gap-3">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-shop-primary-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H3m10 11h2.5a1 1 0 001-.7l1.4-4.2a1 1 0 00-.95-1.3H13"
                    />
                </svg>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-900">Delivery:</span>
                    ৳ 60 inside Dhaka, ৳ 120 outside Dhaka. Delivered in 2–4
                    days.
                </p>
            </div>
            <div class="flex items-start gap-3">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-shop-primary-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"
                    />
                </svg>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-900"
                        >Cash on Delivery</span
                    >
                    available — pay when you receive.
                </p>
            </div>
            <div class="flex items-start gap-3">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-shop-primary-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                    />
                </svg>
                <p class="text-sm text-gray-600">
                    Secure online payment via
                    <span class="font-medium text-gray-900">SSLCommerz</span>.
                </p>
            </div>
        </div>
    </div>
</template>
