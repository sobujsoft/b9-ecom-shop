<script setup lang="ts">
import { shopCategories, shopPriceRanges } from '@/data/shop/catalog';
import { useShopCatalog } from '@/composables/shop/useShopCatalog';
import type { ShopPriceRange } from '@/types/shop';

const {
    category,
    priceRange,
    inStockOnly,
    isFilterDrawerOpen,
    setCategory,
    setPriceRange,
    setInStockOnly,
    clearFilters,
    closeFilterDrawer,
} = useShopCatalog();

function handleCategoryChange(event: Event): void {
    setCategory((event.target as HTMLInputElement).value);
}

function handlePriceChange(event: Event): void {
    setPriceRange(
        (event.target as HTMLInputElement).value as ShopPriceRange,
    );
}

function handleInStockChange(event: Event): void {
    setInStockOnly((event.target as HTMLInputElement).checked);
}
</script>

<template>
    <div>
        <div
            class="fixed inset-0 z-50 bg-black/40 lg:hidden"
            :class="isFilterDrawerOpen ? 'block' : 'hidden'"
            @click="closeFilterDrawer"
        />

        <aside
            id="filters"
            aria-label="Product filters"
            class="fixed inset-y-0 right-0 z-50 w-80 max-w-[85%] translate-x-full overflow-y-auto bg-white p-5 shadow-xl transition-transform duration-300 ease-in-out lg:static lg:z-auto lg:w-auto lg:max-w-none lg:translate-x-0 lg:overflow-visible lg:bg-transparent lg:p-0 lg:shadow-none"
            :class="isFilterDrawerOpen ? '!translate-x-0' : ''"
        >
            <div class="mb-4 flex items-center justify-between lg:hidden">
                <span class="text-lg font-bold text-gray-900">Filters</span>
                <button
                    type="button"
                    aria-label="Close filters"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @click="closeFilterDrawer"
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

            <div class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-xl border border-gray-200 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        Category
                    </h3>
                    <div class="space-y-1.5 text-sm">
                        <label
                            v-for="cat in shopCategories"
                            :key="cat"
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                name="category"
                                :value="cat === 'All Categories' ? 'all' : cat"
                                :checked="
                                    category ===
                                    (cat === 'All Categories' ? 'all' : cat)
                                "
                                class="h-4 w-4 text-shop-primary-600 focus:ring-shop-primary-600"
                                @change="handleCategoryChange"
                            />
                            <span class="text-gray-600">{{ cat }}</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        Price Range
                    </h3>
                    <div class="space-y-1.5 text-sm">
                        <label
                            v-for="range in shopPriceRanges"
                            :key="range.value"
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                name="price"
                                :value="range.value"
                                :checked="priceRange === range.value"
                                class="h-4 w-4 text-shop-primary-600 focus:ring-shop-primary-600"
                                @change="handlePriceChange"
                            />
                            <span class="text-gray-600">{{ range.label }}</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        Availability
                    </h3>
                    <label
                        class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 text-sm hover:bg-gray-50"
                    >
                        <input
                            id="inStockOnly"
                            type="checkbox"
                            :checked="inStockOnly"
                            class="h-4 w-4 rounded text-shop-primary-600 focus:ring-shop-primary-600"
                            @change="handleInStockChange"
                        />
                        <span class="text-gray-600">In Stock only</span>
                    </label>
                </div>

                <button
                    type="button"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @click="clearFilters"
                >
                    Clear all filters
                </button>

                <button
                    type="button"
                    class="w-full rounded-lg bg-shop-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-shop-primary-700 lg:hidden"
                    @click="closeFilterDrawer"
                >
                    Show results
                </button>
            </div>
        </aside>
    </div>
</template>
