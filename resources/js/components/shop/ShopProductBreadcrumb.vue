<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

const { category, categoryHref, productName } = defineProps<{
    category: string;
    categoryHref: string;
    productName: string;
}>();
</script>

<template>
    <nav
        aria-label="Breadcrumb"
        class="border-b border-gray-200 bg-gray-50"
    >
        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500">
                <li>
                    <Link :href="home()" class="hover:text-shop-primary-600"
                        >Home</Link
                    >
                </li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li>
                    <a
                        :href="categoryHref"
                        class="hover:text-shop-primary-600"
                        >{{ category }}</a
                    >
                </li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="font-medium text-gray-900">
                    {{ productName }}
                </li>
            </ol>
        </div>
    </nav>
</template>
