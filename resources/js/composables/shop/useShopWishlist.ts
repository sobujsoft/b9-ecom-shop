import { computed, ref } from 'vue';
import { staticWishlistItems } from '@/data/shop/cart-checkout';
import { useShopCart } from '@/composables/shop/useShopCart';
import { productSlug } from '@/lib/shop/product';
import type { ShopProduct, ShopWishlistItem } from '@/types/shop';

const wishlist = ref<ShopWishlistItem[]>([...staticWishlistItems]);

export function useShopWishlist() {
    const { addToCart } = useShopCart();

    const wishCount = computed(() => wishlist.value.length);

    function isWishlisted(productName: string): boolean {
        return wishlist.value.some((item) => item.name === productName);
    }

    function addToWishlist(product: ShopProduct): void {
        if (isWishlisted(product.name)) {
            return;
        }

        wishlist.value.push({
            ...product,
            slug: product.slug ?? productSlug(product.name),
        });
    }

    function removeFromWishlist(index: number): ShopWishlistItem | undefined {
        const [removed] = wishlist.value.splice(index, 1);

        return removed;
    }

    function removeByName(productName: string): void {
        const index = wishlist.value.findIndex(
            (item) => item.name === productName,
        );

        if (index !== -1) {
            wishlist.value.splice(index, 1);
        }
    }

    function clearWishlist(): void {
        wishlist.value = [];
    }

    function toggleWish(
        isActive: boolean,
        product: ShopProduct,
        showToast: (message: string) => void,
    ): boolean {
        if (isActive) {
            removeByName(product.name);
            showToast(`Removed from wishlist: ${product.name}`);

            return false;
        }

        addToWishlist(product);
        showToast(`Added to wishlist: ${product.name}`);

        return true;
    }

    function addAllToCart(showToast: (message: string) => void): number {
        const inStockItems = wishlist.value.filter((item) => item.inStock);

        inStockItems.forEach((item) => {
            addToCart(
                item.name,
                item.price,
                item.img,
                1,
                item.slug ?? productSlug(item.name),
                false,
            );
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
        isWishlisted,
        addToWishlist,
        removeFromWishlist,
        removeByName,
        clearWishlist,
        toggleWish,
        addAllToCart,
    };
}
