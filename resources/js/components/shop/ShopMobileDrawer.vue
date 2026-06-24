<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useShopUi } from '@/composables/shop/useShopUi';
import { home } from '@/routes';

const { isMobileMenuOpen, closeMobileMenu } = useShopUi();
</script>

<template>
    <div>
        <div
            class="fixed inset-0 z-50 bg-black/40 lg:hidden"
            :class="isMobileMenuOpen ? 'block' : 'hidden'"
            aria-hidden="true"
            @click="closeMobileMenu"
        />

        <aside
            id="mobileDrawer"
            class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85%] bg-white shadow-xl transition-transform duration-300 ease-in-out lg:hidden"
            :class="isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
            role="dialog"
            aria-modal="true"
            aria-label="Menu"
        >
            <div
                class="flex h-16 items-center justify-between border-b border-gray-200 px-4"
            >
                <span class="text-lg font-bold text-gray-900">Menu</span>
                <button
                    type="button"
                    aria-label="Close menu"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    @click="closeMobileMenu"
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
            <div class="p-4">
                <label for="searchMobile" class="sr-only"
                    >Search products</label
                >
                <div class="relative mb-4">
                    <span
                        class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400"
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
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                            />
                        </svg>
                    </span>
                    <input
                        id="searchMobile"
                        type="search"
                        placeholder="Search for products…"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2.5 pr-4 pl-10 text-sm focus:border-shop-primary-600 focus:bg-white focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                    />
                </div>
                <nav class="flex flex-col" aria-label="Mobile">
                    <Link
                        :href="home()"
                        class="rounded-lg px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-100"
                        @click="closeMobileMenu"
                    >
                        Home
                    </Link>
                    <a
                        href="#"
                        class="rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="closeMobileMenu"
                    >
                        Shop
                    </a>
                    <a
                        href="#categories"
                        class="rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="closeMobileMenu"
                    >
                        Categories
                    </a>
                    <a
                        href="#bestselling"
                        class="rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="closeMobileMenu"
                    >
                        Best Selling
                    </a>
                    <a
                        href="#newcollection"
                        class="rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="closeMobileMenu"
                    >
                        New Collection
                    </a>
                    <a
                        href="#"
                        class="mt-2 rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="closeMobileMenu"
                    >
                        My Account
                    </a>
                </nav>
            </div>
        </aside>
    </div>
</template>
