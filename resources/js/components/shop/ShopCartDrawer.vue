<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useShopCart } from '@/composables/shop/useShopCart';
import { formatTaka } from '@/lib/shop/currency';
import shop from '@/routes/shop';

const {
    cart,
    cartQty,
    cartSubtotal,
    isCartOpen,
    closeCart,
    incrementQty,
    decrementQty,
    removeItem,
} = useShopCart();
</script>

<template>
    <div>
        <div
            class="fixed inset-0 z-[60] bg-black/40"
            :class="isCartOpen ? 'block' : 'hidden'"
            @click="closeCart"
        />

        <aside
            class="fixed inset-y-0 right-0 z-[70] flex w-96 max-w-[90%] flex-col bg-white shadow-xl transition-transform duration-300 ease-in-out"
            :class="isCartOpen ? 'translate-x-0' : 'translate-x-full'"
            role="dialog"
            aria-modal="true"
            aria-label="Shopping cart"
        >
            <div
                class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-5"
            >
                <h2 class="text-lg font-semibold text-gray-900">
                    Your Cart ({{ cartQty }})
                </h2>
                <button
                    type="button"
                    aria-label="Close cart"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @click="closeCart"
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
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <div
                v-if="cart.length === 0"
                class="flex flex-1 flex-col items-center justify-center px-6 text-center"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400"
                >
                    <svg
                        class="h-8 w-8"
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
                <p class="font-medium text-gray-900">Your cart is empty</p>
                <p class="mt-1 text-sm text-gray-500">
                    Add items to get started.
                </p>
                <button
                    type="button"
                    class="mt-5 rounded-lg bg-shop-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-shop-primary-700"
                    @click="closeCart"
                >
                    Continue shopping
                </button>
            </div>

            <template v-else>
                <div class="flex-1 overflow-y-auto px-5">
                    <div
                        v-for="(item, index) in cart"
                        :key="`${item.name}-${index}`"
                        class="flex gap-3 border-b border-gray-100 py-4"
                    >
                        <div
                            class="h-16 w-16 shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100"
                        >
                            <img
                                :src="`https://images.unsplash.com/${item.img}?auto=format&fit=crop&w=120&q=70`"
                                :alt="item.name"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div
                                class="flex items-start justify-between gap-2"
                            >
                                <p
                                    class="line-clamp-2 text-sm font-medium text-gray-900"
                                >
                                    {{ item.name }}
                                </p>
                                <button
                                    type="button"
                                    aria-label="Remove"
                                    class="shrink-0 text-gray-300 hover:text-red-600"
                                    @click="removeItem(index)"
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
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                class="mt-2 flex items-center justify-between"
                            >
                                <div
                                    class="inline-flex items-center rounded-lg border border-gray-300"
                                >
                                    <button
                                        type="button"
                                        aria-label="Decrease"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40"
                                        :disabled="item.qty <= 1"
                                        @click="decrementQty(index)"
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
                                                d="M20 12H4"
                                            />
                                        </svg>
                                    </button>
                                    <span
                                        class="w-9 text-center text-sm font-medium text-gray-900"
                                        >{{ item.qty }}</span
                                    >
                                    <button
                                        type="button"
                                        aria-label="Increase"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100"
                                        @click="incrementQty(index)"
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
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900"
                                >
                                    {{ formatTaka(item.price * item.qty) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 border-t border-gray-200 p-5">
                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-sm text-gray-500">Subtotal</span>
                        <span class="text-lg font-bold text-gray-900">{{
                            formatTaka(cartSubtotal)
                        }}</span>
                    </div>
                    <p class="mb-4 text-xs text-gray-400">
                        Delivery calculated at checkout.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <Link
                            :href="shop.cart()"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            @click="closeCart"
                            >View Cart</Link
                        >
                        <Link
                            :href="shop.checkout()"
                            class="rounded-lg bg-shop-primary-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-shop-primary-700"
                            @click="closeCart"
                            >Checkout</Link
                        >
                    </div>
                </div>
            </template>
        </aside>
    </div>
</template>
