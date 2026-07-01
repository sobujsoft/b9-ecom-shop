<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { formatTaka } from '@/lib/shop/currency';
import { productShowUrl } from '@/lib/shop/product';
import type { ShopCartItem } from '@/types/shop';

const { item } = defineProps<{
    item: ShopCartItem;
}>();

const emit = defineEmits<{
    increment: [productId: number];
    decrement: [productId: number];
    remove: [productId: number];
}>();

const productHref = productShowUrl(item);
</script>

<template>
    <li
        class="grid grid-cols-1 gap-3 px-5 py-4 sm:grid-cols-12 sm:items-center sm:gap-4"
    >
        <div class="flex items-center gap-3 sm:col-span-6">
            <Link
                :href="productHref"
                class="h-20 w-20 shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100"
            >
                <img
                    :src="item.img"
                    :alt="item.name"
                    class="h-full w-full object-cover"
                />
            </Link>
            <div class="min-w-0">
                <Link
                    :href="productHref"
                    class="line-clamp-2 text-sm font-medium text-gray-900 hover:text-shop-primary-600"
                >
                    {{ item.name }}
                </Link>
                <button
                    type="button"
                    class="mt-1 inline-flex items-center gap-1 text-xs text-gray-400 transition hover:text-red-600"
                    @click="emit('remove', item.productId)"
                >
                    <svg
                        class="h-3.5 w-3.5"
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
                    Remove
                </button>
            </div>
        </div>
        <div class="text-sm text-gray-600 sm:col-span-2 sm:text-center">
            <span class="text-gray-400 sm:hidden">Price: </span
            >{{ formatTaka(item.price) }}
        </div>
        <div class="sm:col-span-2 sm:flex sm:justify-center">
            <div
                class="inline-flex items-center rounded-lg border border-gray-300"
            >
                <button
                    type="button"
                    :aria-label="`Decrease quantity of ${item.name}`"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none disabled:opacity-40"
                    :disabled="item.qty <= 1"
                    @click="emit('decrement', item.productId)"
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
                    class="w-10 text-center text-sm font-medium text-gray-900"
                    >{{ item.qty }}</span
                >
                <button
                    type="button"
                    :aria-label="`Increase quantity of ${item.name}`"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @click="emit('increment', item.productId)"
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
        </div>
        <div
            class="text-sm font-semibold text-gray-900 sm:col-span-2 sm:text-right"
        >
            <span class="text-gray-400 sm:hidden">Total: </span
            >{{ formatTaka(item.price * item.qty) }}
        </div>
    </li>
</template>
