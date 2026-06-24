<script setup lang="ts">
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopUi } from '@/composables/shop/useShopUi';
import { formatTaka } from '@/lib/shop/currency';
import type { ShopProductDetail } from '@/types/shop';

const { product } = defineProps<{
    product: ShopProductDetail;
}>();

const quantity = defineModel<number>('quantity', { default: 1 });

const { addToCart } = useShopCart();
const { showToast } = useShopUi();

function handleAddToCart(): void {
    addToCart(product.name, product.price, product.img, quantity.value);
    showToast('Added to cart');
}

function handleBuyNow(): void {
    addToCart(product.name, product.price, product.img, quantity.value);
}
</script>

<template>
    <div
        class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 backdrop-blur lg:hidden"
    >
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="leading-tight">
                <p class="text-lg font-bold text-shop-primary-600">
                    {{ formatTaka(product.price) }}
                </p>
                <p
                    v-if="product.oldPrice"
                    class="text-xs text-gray-400 line-through"
                >
                    {{ formatTaka(product.oldPrice) }}
                </p>
            </div>
            <button
                type="button"
                class="flex-1 rounded-lg border border-shop-primary-600 px-4 py-3 text-sm font-semibold text-shop-primary-600 transition hover:bg-shop-primary-50"
                @click="handleAddToCart"
            >
                Add to Cart
            </button>
            <button
                type="button"
                class="flex-1 rounded-lg bg-shop-primary-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-shop-primary-700"
                @click="handleBuyNow"
            >
                Buy Now
            </button>
        </div>
    </div>
</template>
