<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

const { variant = 'light' } = defineProps<{
    variant?: 'light' | 'dark';
}>();
</script>

<template>
    <Link
        :href="home()"
        class="flex items-center gap-2"
        aria-label="ShopEase home"
    >
        <span
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-shop-primary-600 text-white"
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
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
            </svg>
        </span>
        <span
            class="text-xl font-bold"
            :class="variant === 'dark' ? 'text-white' : 'text-gray-900'"
        >
            Shop<span class="text-shop-primary-600">Ease</span>
        </span>
    </Link>
</template>
