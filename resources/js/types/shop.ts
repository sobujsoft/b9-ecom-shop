export type ShopProduct = {
    name: string;
    price: number;
    oldPrice?: number;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    tag?: string;
};

export type ShopCategory = {
    name: string;
    img: string;
    href: string;
};

export type ShopCarouselSlide = {
    src: string;
    alt: string;
};

export type ShopCartItem = {
    name: string;
    price: number;
    img: string;
    qty: number;
};
