<script setup lang="ts">
import { useShopCatalog } from '@/composables/shop/useShopCatalog';
import type { ShopSortOption } from '@/types/shop';

const { total, sort, openFilterDrawer, setSort } = useShopCatalog();

function handleSortChange(event: Event): void {
    setSort((event.target as HTMLSelectElement).value as ShopSortOption);
}
</script>

<template>
    <div class="mb-5 flex items-center justify-between gap-3">
        <p class="text-sm text-gray-600">
            <span class="font-semibold text-gray-900">{{ total }}</span>
            products
        </p>
        <div class="flex items-center gap-2">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 lg:hidden"
                @click="openFilterDrawer"
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
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z"
                    />
                </svg>
                Filters
            </button>
            <div class="flex items-center gap-2">
                <label for="sortSelect" class="hidden text-sm text-gray-500 sm:block"
                    >Sort:</label
                >
                <select
                    id="sortSelect"
                    :value="sort"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-shop-primary-600 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @change="handleSortChange"
                >
                    <option value="newest">Newest</option>
                    <option value="best">Best Selling</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                </select>
            </div>
        </div>
    </div>
</template>
