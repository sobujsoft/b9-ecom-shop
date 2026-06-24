import { ref } from 'vue';

const wishCount = ref(0);

export function useShopWishlist() {
    function toggleWish(
        isActive: boolean,
        productName: string,
        showToast: (message: string) => void,
    ): boolean {
        if (isActive) {
            wishCount.value = Math.max(0, wishCount.value - 1);
            showToast(`Removed from wishlist: ${productName}`);

            return false;
        }

        wishCount.value++;
        showToast(`Added to wishlist: ${productName}`);

        return true;
    }

    return {
        wishCount,
        toggleWish,
    };
}
