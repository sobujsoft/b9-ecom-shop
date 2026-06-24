<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import shop from '@/routes/shop';

const { orderNumber, total, paymentLabel } = defineProps<{
    orderNumber: string;
    total: string;
    paymentLabel: string;
}>();
</script>

<template>
    <div
        class="mx-auto max-w-2xl rounded-2xl border border-gray-200 bg-white p-8 text-center md:p-12"
    >
        <div
            class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-600"
        >
            <svg
                class="h-9 w-9"
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
        </div>
        <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">
            Thank you for your order!
        </h1>
        <p class="mt-2 text-sm text-gray-600 md:text-base">
            Your order has been placed successfully. A confirmation email is on
            its way.
        </p>

        <div
            class="mt-6 grid grid-cols-1 gap-3 rounded-xl bg-gray-50 p-5 text-sm sm:grid-cols-3"
        >
            <div>
                <p class="text-gray-400">Order number</p>
                <p class="mt-0.5 font-semibold text-gray-900">
                    {{ orderNumber }}
                </p>
            </div>
            <div>
                <p class="text-gray-400">Total</p>
                <p class="mt-0.5 font-semibold text-gray-900">{{ total }}</p>
            </div>
            <div>
                <p class="text-gray-400">Payment</p>
                <p class="mt-0.5 font-semibold text-gray-900">
                    {{ paymentLabel }}
                </p>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <Link
                :href="shop.index()"
                class="rounded-lg bg-shop-primary-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700"
            >
                Continue Shopping
            </Link>
            <button
                type="button"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                View Order
            </button>
        </div>
    </div>
</template>
