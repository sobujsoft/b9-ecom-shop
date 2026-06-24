<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ShopOrderConfirmation from '@/components/shop/ShopOrderConfirmation.vue';
import ShopPageBreadcrumb from '@/components/shop/ShopPageBreadcrumb.vue';
import { useShopOrder } from '@/composables/shop/useShopOrder';
import shop from '@/routes/shop';

const { lastOrder } = useShopOrder();
</script>

<template>
    <Head title="Order Confirmed">
        <meta
            name="description"
            content="Your ShopEase order has been placed successfully."
        />
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
            <ShopPageBreadcrumb :items="[{ label: 'Order Confirmed' }]" />

            <ShopOrderConfirmation
                v-if="lastOrder"
                :order-number="lastOrder.orderNumber"
                :total="lastOrder.total"
                :payment-label="lastOrder.paymentLabel"
            />

            <div
                v-else
                class="mx-auto max-w-2xl rounded-2xl border border-gray-200 bg-white p-8 text-center md:p-12"
            >
                <div
                    class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400"
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
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">
                    No order found
                </h1>
                <p class="mt-2 text-sm text-gray-600 md:text-base">
                    We couldn't find a recent order. Place an order from
                    checkout to see your confirmation here.
                </p>
                <Link
                    :href="shop.index()"
                    class="mt-8 inline-block rounded-lg bg-shop-primary-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700"
                >
                    Continue Shopping
                </Link>
            </div>
        </div>
    </div>
</template>
