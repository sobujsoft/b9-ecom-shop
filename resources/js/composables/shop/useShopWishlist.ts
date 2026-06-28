import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useShopCart } from '@/composables/shop/useShopCart';
import type { ShopProduct, ShopWishlist, ShopWishlistItem } from '@/types/shop';

/** True while any wishlist mutation request is in flight. */
const isProcessing = ref(false);

export function useShopWishlist() {
    const inertiaPage = usePage();
    const { addToCart } = useShopCart();

    const serverWishlist = computed(
        (): ShopWishlist =>
            (inertiaPage.props.wishlist as ShopWishlist | undefined) ?? {
                count: 0,
                productIds: [],
                items: [],
            },
    );

    const isAuthenticated = computed(
        () => !!inertiaPage.props.auth?.user,
    );

    const wishlist = computed((): ShopWishlistItem[] => serverWishlist.value.items);
    const wishCount = computed((): number => serverWishlist.value.count);
    const wishlistedProductIds = computed(
        (): number[] => serverWishlist.value.productIds,
    );

    function isWishlisted(productId: number): boolean {
        return wishlistedProductIds.value.includes(productId);
    }

    function addToWishlist(productId: number): void {
        router.post(
            '/wishlist',
            { product_id: productId },
            {
                preserveScroll: true,
                onStart: () => {
                    isProcessing.value = true;
                },
                onFinish: () => {
                    isProcessing.value = false;
                },
            },
        );
    }

    function removeFromWishlist(productId: number): void {
        router.delete(`/wishlist/${productId}`, { preserveScroll: true });
    }

    function clearWishlist(): void {
        router.delete('/wishlist', { preserveScroll: true });
    }

    function toggleWish(
        product: ShopProduct,
        showToast: (message: string) => void,
    ): void {
        if (!product.id) {
            return;
        }

        if (!isAuthenticated.value) {
            showToast('Please log in to save items to your wishlist');

            return;
        }

        if (isWishlisted(product.id)) {
            removeFromWishlist(product.id);
            showToast(`Removed from wishlist: ${product.name}`);

            return;
        }

        addToWishlist(product.id);
        showToast(`Added to wishlist: ${product.name}`);
    }

    function addAllToCart(showToast: (message: string) => void): number {
        const inStockItems = wishlist.value.filter((item) => item.inStock);

        inStockItems.forEach((item) => {
            addToCart(item.id, 1, false);
        });

        if (inStockItems.length === 0) {
            showToast('No in-stock items to add');

            return 0;
        }

        showToast(
            `Added ${inStockItems.length} item${inStockItems.length > 1 ? 's' : ''} to cart`,
        );

        return inStockItems.length;
    }

    return {
        wishlist,
        wishCount,
        wishlistedProductIds,
        isAuthenticated,
        isProcessing,
        isWishlisted,
        addToWishlist,
        removeFromWishlist,
        clearWishlist,
        toggleWish,
        addAllToCart,
    };
}
