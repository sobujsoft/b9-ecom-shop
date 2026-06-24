import { productDetails } from '@/data/shop/product-details';
import type { ShopProduct, ShopProductDetail } from '@/types/shop';

export function productSlug(name: string): string {
    return name
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
}

export function productShowUrl(product: {
    name: string;
    slug?: string;
}): string {
    return `/products/${product.slug ?? productSlug(product.name)}`;
}

export function getProductBySlug(slug: string): ShopProductDetail | null {
    return productDetails[slug] ?? null;
}

export function savingsPercent(price: number, oldPrice: number): number {
    return Math.round(((oldPrice - price) / oldPrice) * 100);
}
