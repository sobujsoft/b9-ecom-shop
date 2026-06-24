<script setup lang="ts">
import { useShopCatalog } from '@/composables/shop/useShopCatalog';

const { activeFilters, removeFilter } = useShopCatalog();
</script>

<template>
    <div
        v-if="activeFilters.length > 0"
        class="mb-5 flex flex-wrap gap-2"
    >
        <button
            v-for="(filter, index) in activeFilters"
            :key="`${filter.label}-${index}`"
            type="button"
            class="inline-flex items-center gap-1.5 rounded-full bg-shop-primary-50 px-3 py-1 text-xs font-medium text-shop-primary-700 transition hover:bg-shop-primary-100"
            @click="removeFilter(index)"
        >
            {{ filter.label }}
            <svg
                class="h-3.5 w-3.5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
    </div>
</template>
