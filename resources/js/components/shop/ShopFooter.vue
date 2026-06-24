<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import ShopLogo from '@/components/shop/ShopLogo.vue';
import { home } from '@/routes';
import shop from '@/routes/shop';

const year = computed(() => new Date().getFullYear());
</script>

<template>
    <footer class="bg-gray-900 text-gray-300">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <div>
                    <ShopLogo variant="dark" />
                    <p class="mt-4 text-sm leading-relaxed text-gray-400">
                        Quality products delivered across Bangladesh. Shop with
                        confidence — Cash on Delivery and secure online payment
                        available.
                    </p>
                    <div class="mt-4 flex gap-3">
                        <a
                            href="#"
                            aria-label="Facebook"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-shop-primary-600"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                />
                            </svg>
                        </a>
                        <a
                            href="#"
                            aria-label="Instagram"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-shop-primary-600"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2.2c3.2 0 3.6 0 4.9.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.86s0 3.6-.07 4.86c-.05 1.17-.25 1.8-.41 2.23a3.7 3.7 0 01-.9 1.38c-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.86.07s-3.6 0-4.86-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.6 2.2 15.2 2.2 12s0-3.6.07-4.86c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.21 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.52.01-4.76.07-.9.04-1.39.2-1.71.32-.43.17-.74.37-1.06.69-.32.32-.52.63-.69 1.06-.12.32-.28.81-.32 1.71-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.9.2 1.39.32 1.71.17.43.37.74.69 1.06.32.32.63.52 1.06.69.32.12.81.28 1.71.32 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.9-.04 1.39-.2 1.71-.32.43-.17.74-.37 1.06-.69.32-.32.52-.63.69-1.06.12-.32.28-.81.32-1.71.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.9-.2-1.39-.32-1.71a2.86 2.86 0 00-.69-1.06 2.86 2.86 0 00-1.06-.69c-.32-.12-.81-.28-1.71-.32-1.24-.06-1.61-.07-4.76-.07zM12 7.4a4.6 4.6 0 100 9.2 4.6 4.6 0 000-9.2zm0 7.6a3 3 0 110-6 3 3 0 010 6zm5.85-7.8a1.08 1.08 0 11-2.15 0 1.08 1.08 0 012.15 0z"
                                />
                            </svg>
                        </a>
                        <a
                            href="#"
                            aria-label="YouTube"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-shop-primary-600"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 00.5 6.2 31.3 31.3 0 000 12a31.3 31.3 0 00.5 5.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A31.3 31.3 0 0024 12a31.3 31.3 0 00-.5-5.8zM9.5 15.5v-7l6.5 3.5-6.5 3.5z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3
                        class="text-sm font-semibold tracking-wide text-white uppercase"
                    >
                        Quick Links
                    </h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li>
                            <Link
                                :href="home()"
                                class="text-gray-400 transition hover:text-white"
                                >Home</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="shop.index()"
                                class="text-gray-400 transition hover:text-white"
                                >Shop</Link
                            >
                        </li>
                        <li>
                            <a
                                href="#bestselling"
                                class="text-gray-400 transition hover:text-white"
                                >Best Selling</a
                            >
                        </li>
                        <li>
                            <a
                                href="#newcollection"
                                class="text-gray-400 transition hover:text-white"
                                >New Collection</a
                            >
                        </li>
                    </ul>
                </div>

                <div>
                    <h3
                        class="text-sm font-semibold tracking-wide text-white uppercase"
                    >
                        Customer Service
                    </h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 transition hover:text-white"
                                >Track Your Order</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 transition hover:text-white"
                                >Shipping &amp; Delivery</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 transition hover:text-white"
                                >Returns &amp; Refunds</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 transition hover:text-white"
                                >FAQ</a
                            >
                        </li>
                    </ul>
                </div>

                <div>
                    <h3
                        class="text-sm font-semibold tracking-wide text-white uppercase"
                    >
                        Contact
                    </h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-2">
                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            <span class="text-gray-400"
                                >House 12, Road 5, Dhanmondi, Dhaka 1205</span
                            >
                        </li>
                        <li class="flex items-center gap-2">
                            <svg
                                class="h-5 w-5 shrink-0 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                />
                            </svg>
                            <a
                                href="tel:+8801700000000"
                                class="text-gray-400 transition hover:text-white"
                                >+880 1700-000000</a
                            >
                        </li>
                        <li class="flex items-center gap-2">
                            <svg
                                class="h-5 w-5 shrink-0 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <a
                                href="mailto:support@shopease.com.bd"
                                class="text-gray-400 transition hover:text-white"
                                >support@shopease.com.bd</a
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-10 flex flex-col items-center gap-4 border-t border-white/10 pt-6 sm:flex-row sm:justify-between"
            >
                <p class="text-xs text-gray-400">
                    © {{ year }} ShopEase. All rights reserved.
                </p>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400">We accept</span>
                    <span
                        class="rounded bg-white/10 px-2 py-1 text-xs font-medium text-gray-200"
                        >Cash on Delivery</span
                    >
                    <span
                        class="rounded bg-white/10 px-2 py-1 text-xs font-medium text-gray-200"
                        >SSLCommerz</span
                    >
                </div>
            </div>
        </div>
    </footer>
</template>
