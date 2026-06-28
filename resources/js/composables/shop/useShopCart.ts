import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import type { ShopCart, ShopCartItem } from '@/types/shop';

// ─── Module-level singletons ──────────────────────────────────────────────────

const isCartOpen = ref(false);

/** True while any cart mutation request is in flight. */
const isProcessing = ref(false);

// ─── Composable ───────────────────────────────────────────────────────────────

export function useShopCart() {
    const inertiaPage = usePage();

    // ── Server state ────────────────────────────────────────────────────────

    const serverCart = computed(
        (): ShopCart =>
            (inertiaPage.props.cart as ShopCart | undefined) ?? {
                qty: 0,
                items: [],
            },
    );

    const cart = computed((): ShopCartItem[] => serverCart.value.items);
    const cartQty = computed((): number => serverCart.value.qty);
    const cartSubtotal = computed((): number =>
        cart.value.reduce((sum, item) => sum + item.price * item.qty, 0),
    );

    // ── UI actions (local only) ─────────────────────────────────────────────

    function openCart(): void {
        isCartOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeCart(): void {
        isCartOpen.value = false;
        document.body.style.overflow = '';
    }

    // ── Cart mutations (server-side via Inertia) ────────────────────────────

    /**
     * Add a product to the cart by its database ID.
     * Opens the cart drawer by default.
     */
    function addToCart(
        productId: number,
        qty = 1,
        openDrawer = true,
    ): void {
        router.post(
            '/cart',
            { product_id: productId, qty },
            {
                preserveScroll: true,
                onStart: () => {
                    isProcessing.value = true;
                },
                onFinish: () => {
                    isProcessing.value = false;
                },
                onSuccess: () => {
                    if (openDrawer) {
                        openCart();
                    }
                },
            },
        );
    }

    /**
     * Set the exact quantity for a cart item (server-side, min 1).
     */
    function updateQty(productId: number, qty: number): void {
        router.patch(`/cart/${productId}`, { qty }, { preserveScroll: true });
    }

    /**
     * Remove a specific product from the cart.
     */
    function removeItem(productId: number): void {
        router.delete(`/cart/${productId}`, { preserveScroll: true });
    }

    /**
     * Remove all items from the cart.
     */
    function clearCart(): void {
        router.delete('/cart', { preserveScroll: true });
    }

    return {
        cart,
        cartQty,
        cartSubtotal,
        isCartOpen,
        isProcessing,
        openCart,
        closeCart,
        addToCart,
        updateQty,
        removeItem,
        clearCart,
    };
}
