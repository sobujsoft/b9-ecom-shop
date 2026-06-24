<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import ShopCartDrawer from '@/components/shop/ShopCartDrawer.vue';
import ShopFooter from '@/components/shop/ShopFooter.vue';
import ShopHeader from '@/components/shop/ShopHeader.vue';
import ShopMobileDrawer from '@/components/shop/ShopMobileDrawer.vue';
import ShopToast from '@/components/shop/ShopToast.vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopCatalog } from '@/composables/shop/useShopCatalog';
import { useShopUi } from '@/composables/shop/useShopUi';

const { closeCart } = useShopCart();
const { closeFilterDrawer, isFilterDrawerOpen } = useShopCatalog();
const { closeMobileMenu } = useShopUi();

function handleEscape(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        closeMobileMenu();
        closeCart();

        if (isFilterDrawerOpen.value) {
            closeFilterDrawer();
        }
    }
}

onMounted(() => {
    document.documentElement.classList.add('scroll-smooth');
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.documentElement.classList.remove('scroll-smooth');
    document.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
});
</script>

<template>
    <div class="font-shop bg-white text-gray-600 antialiased">
        <a
            href="#main"
            class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-gray-900 focus:ring-2 focus:ring-shop-primary-600"
            >Skip to content</a
        >

        <ShopHeader />
        <ShopMobileDrawer />

        <main id="main">
            <slot />
        </main>

        <ShopFooter />
        <ShopCartDrawer />
        <ShopToast />
    </div>
</template>
