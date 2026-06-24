<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ShopPageBreadcrumb from '@/components/shop/ShopPageBreadcrumb.vue';
import ShopWishlistCard from '@/components/shop/ShopWishlistCard.vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopUi } from '@/composables/shop/useShopUi';
import { useShopWishlist } from '@/composables/shop/useShopWishlist';
import { productSlug } from '@/lib/shop/product';
import shop from '@/routes/shop';

const { addToCart } = useShopCart();
const { showToast } = useShopUi();
const {
    wishlist,
    clearWishlist,
    removeFromWishlist,
    addAllToCart,
} = useShopWishlist();

function handleRemove(index: number): void {
    const removed = removeFromWishlist(index);

    if (removed) {
        showToast(`Removed from wishlist: ${removed.name}`);
    }
}

function handleAddToCart(index: number): void {
    const item = wishlist.value[index];

    addToCart(
        item.name,
        item.price,
        item.img,
        1,
        item.slug ?? productSlug(item.name),
        false,
    );
    showToast(`Added to cart: ${item.name}`);
}

function handleClearWishlist(): void {
    clearWishlist();
    showToast('Wishlist cleared');
}

function handleAddAllToCart(): void {
    addAllToCart(showToast);
}
</script>

<template>
    <Head title="My Wishlist">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="anonymous"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div class="py-6 md:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <ShopPageBreadcrumb :items="[{ label: 'Wishlist' }]" />

            <div
                v-if="wishlist.length > 0"
                class="mb-6 flex flex-wrap items-center justify-between gap-3"
            >
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">
                        My Wishlist
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ wishlist.length }} items saved
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @click="handleClearWishlist"
                    >
                        Clear all
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-shop-primary-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @click="handleAddAllToCart"
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
                        Add all to cart
                    </button>
                </div>
            </div>

            <div
                v-if="wishlist.length > 0"
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-4"
            >
                <ShopWishlistCard
                    v-for="(item, index) in wishlist"
                    :key="item.name"
                    :item="item"
                    :index="index"
                    @remove="handleRemove"
                    @add-to-cart="handleAddToCart"
                />
            </div>

            <div v-else class="py-16 text-center">
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-red-400"
                >
                    <svg
                        class="h-10 w-10"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Your wishlist is empty
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Tap the heart on any product to save it here for later.
                </p>
                <Link
                    :href="shop.index()"
                    class="mt-5 inline-block rounded-lg bg-shop-primary-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700"
                >
                    Browse Products
                </Link>
            </div>
        </div>
    </div>
</template>
