import { computed, ref } from 'vue';
import type { ShopCartItem } from '@/types/shop';

const cart = ref<ShopCartItem[]>([
    {
        name: 'Wireless Noise-Cancelling Headphones',
        slug: 'wireless-noise-cancelling-headphones',
        price: 6499,
        qty: 1,
        img: 'photo-1505740420928-5e560c06d30e',
    },
    {
        name: 'Smart Fitness Watch Series 6',
        slug: 'smart-fitness-watch-series-6',
        price: 4299,
        qty: 1,
        img: 'photo-1523275335684-37898b6baf30',
    },
    {
        name: 'Classic Leather Sneakers',
        slug: 'classic-leather-sneakers',
        price: 2999,
        qty: 2,
        img: 'photo-1542291026-7eec264c27ff',
    },
]);

const isCartOpen = ref(false);

export function useShopCart() {
    const cartQty = computed(() =>
        cart.value.reduce((sum, item) => sum + item.qty, 0),
    );

    const cartSubtotal = computed(() =>
        cart.value.reduce((sum, item) => sum + item.price * item.qty, 0),
    );

    function openCart(): void {
        isCartOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeCart(): void {
        isCartOpen.value = false;
        document.body.style.overflow = '';
    }

    function addToCart(
        name: string,
        price: number,
        img: string,
        qty = 1,
        slug?: string,
        openDrawer = true,
    ): void {
        const quantity = Math.max(1, qty);
        const existing = cart.value.find((item) => item.name === name);

        if (existing) {
            existing.qty += quantity;
        } else {
            cart.value.push({ name, price, img, qty: quantity, slug });
        }

        if (openDrawer) {
            openCart();
        }
    }

    function incrementQty(index: number): void {
        cart.value[index].qty++;
    }

    function decrementQty(index: number): void {
        if (cart.value[index].qty > 1) {
            cart.value[index].qty--;
        }
    }

    function removeItem(index: number): ShopCartItem | undefined {
        const [removed] = cart.value.splice(index, 1);

        return removed;
    }

    function clearCart(): void {
        cart.value = [];
    }

    return {
        cart,
        cartQty,
        cartSubtotal,
        isCartOpen,
        openCart,
        closeCart,
        addToCart,
        incrementQty,
        decrementQty,
        removeItem,
        clearCart,
    };
}
