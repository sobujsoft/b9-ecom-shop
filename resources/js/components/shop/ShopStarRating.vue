<script setup lang="ts">
const { rating, size = 'md' } = defineProps<{
    rating: number;
    size?: 'sm' | 'md' | 'lg';
}>();

const sizeClass = {
    sm: 'h-3.5 w-3.5',
    md: 'h-4 w-4',
    lg: 'h-5 w-5',
}[size];

const fullStars = Math.round(rating);
</script>

<template>
    <div class="flex gap-0.5" :aria-label="`Rated ${rating} out of 5`">
        <svg
            v-for="star in 5"
            :key="star"
            :class="[
                sizeClass,
                star <= fullStars ? 'text-shop-accent-500' : 'text-gray-300',
            ]"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path
                d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"
            />
        </svg>
    </div>
</template>
