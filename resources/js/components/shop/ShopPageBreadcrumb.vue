<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

export type ShopBreadcrumbItem = {
    label: string;
    href?: string;
};

const { items } = defineProps<{
    items: ShopBreadcrumbItem[];
}>();
</script>

<template>
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500">
            <li>
                <Link :href="home()" class="hover:text-shop-primary-600"
                    >Home</Link
                >
            </li>
            <template v-for="(item, index) in items" :key="index">
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li
                    :aria-current="
                        index === items.length - 1 ? 'page' : undefined
                    "
                    :class="
                        index === items.length - 1
                            ? 'font-medium text-gray-900'
                            : ''
                    "
                >
                    <Link
                        v-if="item.href"
                        :href="item.href"
                        class="hover:text-shop-primary-600"
                    >
                        {{ item.label }}
                    </Link>
                    <span v-else>{{ item.label }}</span>
                </li>
            </template>
        </ol>
    </nav>
</template>
