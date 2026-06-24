<script setup lang="ts">
import { ref } from 'vue';
import ShopStarRating from '@/components/shop/ShopStarRating.vue';
import { useShopUi } from '@/composables/shop/useShopUi';
import type { ShopProductDetail } from '@/types/shop';

const { product } = defineProps<{
    product: ShopProductDetail;
}>();

const { showToast } = useShopUi();

const activeTab = ref<'description' | 'reviews'>('description');
const selectedRating = ref(0);
const hoverRating = ref(0);
const reviewName = ref('');
const reviewText = ref('');

function activateTab(tab: 'description' | 'reviews'): void {
    activeTab.value = tab;
}

function displayRating(): number {
    return hoverRating.value || selectedRating.value;
}

function setRating(value: number): void {
    selectedRating.value = value;
}

function submitReview(event: Event): void {
    event.preventDefault();

    if (!selectedRating.value) {
        showToast('Please select a star rating');

        return;
    }

    showToast('Thanks! Your review is pending approval.');
    reviewName.value = '';
    reviewText.value = '';
    selectedRating.value = 0;
    hoverRating.value = 0;
}
</script>

<template>
    <section class="border-t border-gray-200 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 md:py-14 lg:px-8">
            <div class="border-b border-gray-200">
                <div
                    class="flex gap-6"
                    role="tablist"
                    aria-label="Product information"
                >
                    <button
                        id="tab-desc"
                        role="tab"
                        :aria-selected="activeTab === 'description'"
                        aria-controls="panel-desc"
                        class="-mb-px border-b-2 px-1 pb-3 text-sm font-semibold"
                        :class="
                            activeTab === 'description'
                                ? 'border-shop-primary-600 text-shop-primary-600'
                                : 'border-transparent text-gray-500 hover:text-gray-900'
                        "
                        @click="activateTab('description')"
                    >
                        Description
                    </button>
                    <button
                        id="tab-rev"
                        role="tab"
                        :aria-selected="activeTab === 'reviews'"
                        aria-controls="panel-rev"
                        class="-mb-px border-b-2 px-1 pb-3 text-sm font-semibold"
                        :class="
                            activeTab === 'reviews'
                                ? 'border-shop-primary-600 text-shop-primary-600'
                                : 'border-transparent text-gray-500 hover:text-gray-900'
                        "
                        @click="activateTab('reviews')"
                    >
                        Reviews
                        <span class="text-gray-400"
                            >({{ product.reviews }})</span
                        >
                    </button>
                </div>
            </div>

            <div
                v-show="activeTab === 'description'"
                id="panel-desc"
                role="tabpanel"
                aria-labelledby="tab-desc"
                class="pt-6"
            >
                <div
                    class="max-w-prose space-y-4 text-sm leading-relaxed text-gray-600 md:text-base"
                >
                    <p v-for="(paragraph, index) in product.description" :key="index">
                        {{ paragraph }}
                    </p>
                    <h3 class="pt-2 text-base font-semibold text-gray-900">
                        Key Features
                    </h3>
                    <ul class="list-disc space-y-1.5 pl-5">
                        <li
                            v-for="(feature, index) in product.features"
                            :key="index"
                        >
                            {{ feature }}
                        </li>
                    </ul>
                </div>
            </div>

            <div
                v-show="activeTab === 'reviews'"
                id="panel-rev"
                role="tabpanel"
                aria-labelledby="tab-rev"
                class="pt-6"
                tabindex="-1"
            >
                <div id="reviews" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-1">
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-6 text-center"
                        >
                            <p class="text-5xl font-bold text-gray-900">
                                {{ product.rating }}
                            </p>
                            <div class="mt-2 flex justify-center">
                                <ShopStarRating
                                    :rating="product.rating"
                                    size="lg"
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Based on {{ product.reviews }} reviews
                            </p>
                        </div>

                        <div class="mt-4 space-y-2">
                            <div
                                v-for="item in product.ratingBreakdown"
                                :key="item.stars"
                                class="flex items-center gap-2 text-sm"
                            >
                                <span class="w-8 shrink-0 text-gray-600"
                                    >{{ item.stars }} ★</span
                                >
                                <div
                                    class="h-2 flex-1 overflow-hidden rounded-full bg-gray-200"
                                >
                                    <div
                                        class="h-full rounded-full bg-shop-accent-500"
                                        :style="{
                                            width: `${item.percent}%`,
                                        }"
                                    />
                                </div>
                                <span
                                    class="w-9 shrink-0 text-right text-gray-400"
                                    >{{ item.percent }}%</span
                                >
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                            <div
                                v-for="(review, index) in product.reviewList"
                                :key="index"
                                class="rounded-xl border border-gray-200 bg-white p-5"
                            >
                                <div
                                    class="flex items-center justify-between gap-3"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-shop-primary-50 text-sm font-semibold text-shop-primary-600"
                                            >{{ review.name.charAt(0) }}</span
                                        >
                                        <div>
                                            <p
                                                class="text-sm font-semibold text-gray-900"
                                            >
                                                {{ review.name }}
                                                <span
                                                    v-if="review.verified"
                                                    class="ml-1 align-middle rounded bg-green-50 px-1.5 py-0.5 text-[10px] font-medium text-green-700"
                                                    >Verified</span
                                                >
                                            </p>
                                            <div class="mt-0.5">
                                                <ShopStarRating
                                                    :rating="review.rating"
                                                    size="sm"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{
                                        review.date
                                    }}</span>
                                </div>
                                <p
                                    class="mt-3 text-sm leading-relaxed text-gray-600"
                                >
                                    {{ review.text }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-10 rounded-xl border border-gray-200 bg-white p-6"
                        >
                            <h3 class="text-lg font-semibold text-gray-900">
                                Write a review
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Your review will appear after approval by our
                                team.
                            </p>
                            <form class="mt-4 space-y-4" @submit="submitReview">
                                <div>
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-gray-700"
                                        >Your rating</span
                                    >
                                    <div
                                        class="flex gap-1"
                                        role="radiogroup"
                                        aria-label="Your rating"
                                        @mouseleave="hoverRating = 0"
                                    >
                                        <button
                                            v-for="star in 5"
                                            :key="star"
                                            type="button"
                                            role="radio"
                                            :aria-checked="
                                                selectedRating === star
                                            "
                                            :aria-label="`${star} star`"
                                            class="rounded p-0.5 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                            @click="setRating(star)"
                                            @mouseenter="hoverRating = star"
                                        >
                                            <svg
                                                class="h-7 w-7"
                                                :class="
                                                    star <= displayRating()
                                                        ? 'text-shop-accent-500'
                                                        : 'text-gray-300'
                                                "
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        for="reviewName"
                                        class="mb-1.5 block text-sm font-medium text-gray-700"
                                        >Name</label
                                    >
                                    <input
                                        id="reviewName"
                                        v-model="reviewName"
                                        type="text"
                                        required
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-shop-primary-600 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                        placeholder="Your name"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="reviewText"
                                        class="mb-1.5 block text-sm font-medium text-gray-700"
                                        >Review</label
                                    >
                                    <textarea
                                        id="reviewText"
                                        v-model="reviewText"
                                        rows="4"
                                        required
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-shop-primary-600 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                        placeholder="Share your experience with this product…"
                                    />
                                </div>
                                <button
                                    type="submit"
                                    class="rounded-lg bg-shop-primary-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-shop-primary-700 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                >
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
