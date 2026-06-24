<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { formatTaka } from '@/lib/shop/currency';
import shop from '@/routes/shop';

const { itemCount, subtotal } = defineProps<{
    itemCount: number;
    subtotal: number;
}>();
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-5 lg:sticky lg:top-24"
    >
        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
        <div class="mt-4 space-y-2.5 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500"
                    >Subtotal ({{ itemCount }} items)</span
                >
                <span class="font-medium text-gray-900">{{
                    formatTaka(subtotal)
                }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Delivery</span>
                <span class="text-gray-400">Calculated at checkout</span>
            </div>
            <div
                class="mt-2 flex justify-between border-t border-gray-100 pt-3 text-base"
            >
                <span class="font-semibold text-gray-900">Estimated total</span>
                <span class="font-bold text-shop-primary-600">{{
                    formatTaka(subtotal)
                }}</span>
            </div>
        </div>
        <Link
            :href="shop.checkout()"
            class="mt-5 block w-full rounded-lg bg-shop-primary-600 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-shop-primary-700 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
        >
            Proceed to Checkout
        </Link>
        <div class="mt-4 space-y-2 text-xs text-gray-500">
            <p class="flex items-center gap-2">
                <svg
                    class="h-4 w-4 text-green-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                Cash on Delivery available
            </p>
            <p class="flex items-center gap-2">
                <svg
                    class="h-4 w-4 text-green-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                Secure payment via SSLCommerz
            </p>
        </div>
    </div>
</template>
