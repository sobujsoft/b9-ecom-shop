import { computed, ref } from 'vue';
import type { ShopCartItem } from '@/types/shop';

const cart = ref<ShopCartItem[]>([]);
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
    ): void {
        const quantity = Math.max(1, qty);
        const existing = cart.value.find((item) => item.name === name);

        if (existing) {
            existing.qty += quantity;
        } else {
            cart.value.push({ name, price, img, qty: quantity });
        }

        openCart();
    }

    function incrementQty(index: number): void {
        cart.value[index].qty++;
    }

    function decrementQty(index: number): void {
        if (cart.value[index].qty > 1) {
            cart.value[index].qty--;
        }
    }

    function removeItem(index: number): void {
        cart.value.splice(index, 1);
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
    };
}
