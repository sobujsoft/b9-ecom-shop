<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ShopCartLineItem from '@/components/shop/ShopCartLineItem.vue';
import ShopCartSummary from '@/components/shop/ShopCartSummary.vue';
import ShopPageBreadcrumb from '@/components/shop/ShopPageBreadcrumb.vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopUi } from '@/composables/shop/useShopUi';
import shop from '@/routes/shop';

const {
    cart,
    cartQty,
    cartSubtotal,
    incrementQty,
    decrementQty,
    removeItem,
    clearCart,
} = useShopCart();
const { showToast } = useShopUi();

function handleRemove(index: number): void {
    const removed = removeItem(index);

    if (removed) {
        showToast(`Removed: ${removed.name}`);
    }
}

function handleClearCart(): void {
    clearCart();
    showToast('Cart cleared');
}
</script>

<template>
    <Head title="Shopping Cart">
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

    <div class="bg-gray-50 py-6 md:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <ShopPageBreadcrumb :items="[{ label: 'Cart' }]" />

            <h1 class="mb-6 text-2xl font-bold text-gray-900 md:text-3xl">
                Shopping Cart
            </h1>

            <div
                v-if="cart.length > 0"
                class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8"
            >
                <div class="lg:col-span-2">
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white"
                    >
                        <div
                            class="hidden grid-cols-12 gap-4 border-b border-gray-100 px-5 py-3 text-xs font-semibold tracking-wide text-gray-400 uppercase sm:grid"
                        >
                            <span class="col-span-6">Product</span>
                            <span class="col-span-2 text-center">Price</span>
                            <span class="col-span-2 text-center"
                                >Quantity</span
                            >
                            <span class="col-span-2 text-right">Total</span>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            <ShopCartLineItem
                                v-for="(item, index) in cart"
                                :key="`${item.name}-${index}`"
                                :item="item"
                                :index="index"
                                @increment="incrementQty"
                                @decrement="decrementQty"
                                @remove="handleRemove"
                            />
                        </ul>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <Link
                            :href="shop.index()"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-shop-primary-600 hover:text-shop-primary-700"
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
                                    d="M19 12H5M11 18l-6-6 6-6"
                                />
                            </svg>
                            Continue shopping
                        </Link>
                        <button
                            type="button"
                            class="text-sm font-medium text-gray-500 transition hover:text-red-600"
                            @click="handleClearCart"
                        >
                            Clear cart
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <ShopCartSummary
                        :item-count="cartQty"
                        :subtotal="cartSubtotal"
                    />
                </div>
            </div>

            <div
                v-else
                class="rounded-xl border border-gray-200 bg-white py-16 text-center"
            >
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 text-gray-400"
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
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Your cart is empty
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Looks like you haven't added anything yet.
                </p>
                <Link
                    :href="shop.index()"
                    class="mt-5 inline-block rounded-lg bg-shop-primary-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700"
                >
                    Start Shopping
                </Link>
            </div>
        </div>
    </div>
</template>
