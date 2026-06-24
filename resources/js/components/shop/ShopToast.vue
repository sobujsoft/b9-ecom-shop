<script setup lang="ts">
import { useShopUi } from '@/composables/shop/useShopUi';

const { toastMessage, isToastVisible } = useShopUi();
</script>

<template>
    <div
        class="pointer-events-none fixed top-20 right-4 z-[80] rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-lg transition-all duration-300"
        :class="
            isToastVisible
                ? 'translate-y-0 opacity-100'
                : 'translate-y-2 opacity-0'
        "
    >
        {{ toastMessage }}
    </div>
</template>
