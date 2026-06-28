export type ShopProduct = {
    name: string;
    slug?: string;
    price: number;
    oldPrice?: number;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    tag?: string;
};

export type ShopCatalogProduct = ShopProduct & {
    id: number;
    category: string;
    sold: number;
};

export type ShopSortOption = 'newest' | 'best' | 'price-asc' | 'price-desc';

export type ShopPriceRange =
    | 'all'
    | '0-1000'
    | '1000-3000'
    | '3000-6000'
    | '6000-';

export type ShopProductImage = {
    full: string;
    thumb: string;
};

export type ShopRatingBreakdown = {
    stars: number;
    percent: number;
};

export type ShopReview = {
    name: string;
    rating: number;
    date: string;
    verified: boolean;
    text: string;
};

export type ShopProductDetail = ShopProduct & {
    slug: string;
    category: string;
    categoryHref: string;
    summary: string;
    description: string[];
    features: string[];
    images: ShopProductImage[];
    ratingBreakdown: ShopRatingBreakdown[];
    reviewList: ShopReview[];
};

export type ShopCategoryFilter = {
    name: string;
    slug: string;
};

export type ShopFilters = {
    categories: string[];
    price: string;
    inStock: boolean;
    sort: string;
    search: string;
    page: number;
};

export type ShopPaginationMeta = {
    total: number;
    perPage: number;
    currentPage: number;
    lastPage: number;
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
    slug?: string;
    price: number;
    img: string;
    qty: number;
};

export type ShopWishlistItem = ShopProduct;

export type ShopPlacedOrder = {
    orderNumber: string;
    total: string;
    paymentLabel: string;
};
