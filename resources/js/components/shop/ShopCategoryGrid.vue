<script setup lang="ts">
import { categories } from '@/data/shop/static';
</script>

<template>
    <section id="categories" class="bg-white py-12 md:py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 md:text-3xl">
                        Shop by category
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 md:text-base">
                        Find exactly what you're looking for.
                    </p>
                </div>
                <a
                    href="#"
                    class="hidden items-center gap-1 text-sm font-medium text-shop-primary-600 hover:text-shop-primary-700 sm:inline-flex"
                >
                    All categories
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
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </a>
            </div>

            <div
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-6"
            >
                <a
                    v-for="category in categories"
                    :key="category.name"
                    :href="category.href"
                    class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-shop-primary-600 hover:shadow-xl"
                >
                    <div
                        class="relative aspect-square overflow-hidden bg-gray-100"
                    >
                        <img
                            :src="category.img"
                            :alt="category.name"
                            loading="lazy"
                            class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110"
                        />
                        <div
                            class="pointer-events-none absolute inset-0 flex items-end justify-end bg-gradient-to-t from-black/45 via-black/5 to-transparent p-2 opacity-0 transition duration-300 group-hover:opacity-100"
                        >
                            <span
                                class="inline-flex h-8 w-8 translate-y-1 items-center justify-center rounded-full bg-white text-shop-primary-600 shadow transition duration-300 group-hover:translate-y-0"
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
                                        d="M5 12h14M13 6l6 6-6 6"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <span
                        class="block px-2 py-3 text-center text-sm font-medium text-gray-900 transition group-hover:text-shop-primary-600"
                        >{{ category.name }}</span
                    >
                </a>
            </div>
        </div>
    </section>
</template>
