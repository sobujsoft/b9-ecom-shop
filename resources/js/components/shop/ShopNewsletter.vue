<script setup lang="ts">
import { useShopUi } from '@/composables/shop/useShopUi';

const { showToast } = useShopUi();

function handleSubmit(event: Event): void {
    event.preventDefault();
    showToast('Subscribed! Check your inbox 🎉');
}
</script>

<template>
    <section class="bg-shop-primary-600">
        <div
            class="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16 lg:px-8"
        >
            <div
                class="flex flex-col items-center gap-6 text-center md:flex-row md:justify-between md:text-left"
            >
                <div class="max-w-lg">
                    <h2 class="text-2xl font-bold text-white md:text-3xl">
                        Get 10% off your first order
                    </h2>
                    <p
                        class="mt-2 text-sm text-blue-100 md:text-base"
                    >
                        Subscribe for new arrivals, exclusive deals, and offers
                        delivered to your inbox.
                    </p>
                </div>
                <form
                    class="flex w-full max-w-md flex-col gap-3 sm:flex-row"
                    @submit="handleSubmit"
                >
                    <label for="newsletterEmail" class="sr-only"
                        >Email address</label
                    >
                    <input
                        id="newsletterEmail"
                        type="email"
                        required
                        placeholder="Enter your email"
                        class="w-full rounded-lg border border-transparent px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-white focus:outline-none"
                    />
                    <button
                        type="submit"
                        class="shrink-0 rounded-lg bg-shop-accent-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-accent-600 focus:ring-2 focus:ring-white focus:outline-none"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
