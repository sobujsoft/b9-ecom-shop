<script setup lang="ts">
import { computed } from 'vue';
import { useShopCatalog } from '@/composables/shop/useShopCatalog';

const { page, totalPages, setPage } = useShopCatalog();

const pages = computed(() =>
    Array.from({ length: totalPages.value }, (_, i) => i + 1),
);
</script>

<template>
    <nav
        v-if="totalPages > 1"
        class="mt-10 flex items-center justify-center gap-1.5"
        aria-label="Pagination"
    >
        <button
            type="button"
            :disabled="page === 1"
            aria-label="Previous page"
            class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border px-3 text-sm font-medium transition"
            :class="
                page === 1
                    ? 'cursor-not-allowed border-gray-300 text-gray-700 opacity-40'
                    : 'border-gray-300 text-gray-700 hover:bg-gray-50'
            "
            @click="setPage(page - 1)"
        >
            ‹
        </button>

        <button
            v-for="pageNumber in pages"
            :key="pageNumber"
            type="button"
            class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border px-3 text-sm font-medium transition"
            :class="
                pageNumber === page
                    ? 'border-shop-primary-600 bg-shop-primary-600 text-white'
                    : 'border-gray-300 text-gray-700 hover:bg-gray-50'
            "
            @click="setPage(pageNumber)"
        >
            {{ pageNumber }}
        </button>

        <button
            type="button"
            :disabled="page === totalPages"
            aria-label="Next page"
            class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border px-3 text-sm font-medium transition"
            :class="
                page === totalPages
                    ? 'cursor-not-allowed border-gray-300 text-gray-700 opacity-40'
                    : 'border-gray-300 text-gray-700 hover:bg-gray-50'
            "
            @click="setPage(page + 1)"
        >
            ›
        </button>
    </nav>
</template>
