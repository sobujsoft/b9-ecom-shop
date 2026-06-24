import { ref } from 'vue';
import type { ShopPlacedOrder } from '@/types/shop';

const lastOrder = ref<ShopPlacedOrder | null>(null);

export function useShopOrder() {
    function setLastOrder(order: ShopPlacedOrder): void {
        lastOrder.value = order;
    }

    function clearLastOrder(): void {
        lastOrder.value = null;
    }

    return {
        lastOrder,
        setLastOrder,
        clearLastOrder,
    };
}
