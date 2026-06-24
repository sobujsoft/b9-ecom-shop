<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ShopCatalogToolbar from '@/components/shop/ShopCatalogToolbar.vue';
import ShopFilterChips from '@/components/shop/ShopFilterChips.vue';
import ShopFilters from '@/components/shop/ShopFilters.vue';
import ShopPagination from '@/components/shop/ShopPagination.vue';
import ShopProductCard from '@/components/shop/ShopProductCard.vue';
import { useShopCatalog } from '@/composables/shop/useShopCatalog';
import { home } from '@/routes';

const { filteredProducts, paginatedProducts, clearFilters } = useShopCatalog();
</script>

<template>
    <Head title="Shop">
        <meta
            name="description"
            content="Browse all products on ShopEase. Filter by category and price, sort, and search. Cash on Delivery across Bangladesh."
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

    <div class="border-b border-gray-200 bg-gray-50">
        <div
            class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 gap-y-1 px-4 py-3 sm:px-6 lg:px-8"
        >
            <h1 class="text-lg font-bold text-gray-900 md:text-xl">
                Shop All Products
            </h1>
            <nav aria-label="Breadcrumb">
                <ol
                    class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500"
                >
                    <li>
                        <Link :href="home()" class="hover:text-shop-primary-600"
                            >Home</Link
                        >
                    </li>
                    <li aria-hidden="true" class="text-gray-300">/</li>
                    <li aria-current="page" class="font-medium text-gray-700">
                        Shop
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-[260px_1fr] lg:gap-8">
            <ShopFilters />

            <div>
                <ShopCatalogToolbar />
                <ShopFilterChips />

                <div
                    v-if="filteredProducts.length > 0"
                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <ShopProductCard
                        v-for="product in paginatedProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>

                <div
                    v-else
                    class="py-16 text-center"
                >
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400"
                    >
                        <svg
                            class="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        No products found
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Try adjusting your filters or search.
                    </p>
                    <button
                        type="button"
                        class="mt-4 rounded-lg bg-shop-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-shop-primary-700"
                        @click="clearFilters"
                    >
                        Clear filters
                    </button>
                </div>

                <ShopPagination />
            </div>
        </div>
    </div>
</template>
