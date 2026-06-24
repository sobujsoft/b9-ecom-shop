import type {
    ShopCarouselSlide,
    ShopCategory,
    ShopProduct,
} from '@/types/shop';

export const carouselSlides: ShopCarouselSlide[] = [
    {
        src: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1920&q=70',
        alt: 'Featured promotion banner',
    },
    {
        src: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1920&q=70',
        alt: 'Featured promotion banner',
    },
    {
        src: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1920&q=70',
        alt: 'Featured promotion banner',
    },
];

export const categories: ShopCategory[] = [
    {
        name: 'Electronics',
        img: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=70',
        href: '#',
    },
    {
        name: 'Fashion',
        img: 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=400&q=70',
        href: '#',
    },
    {
        name: 'Home & Living',
        img: 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=400&q=70',
        href: '#',
    },
    {
        name: 'Beauty',
        img: 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=70',
        href: '#',
    },
    {
        name: 'Sports',
        img: 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=400&q=70',
        href: '#',
    },
    {
        name: 'Books',
        img: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70',
        href: '#',
    },
];

export const bestSellingProducts: ShopProduct[] = [
    {
        name: 'Wireless Noise-Cancelling Headphones',
        slug: 'wireless-noise-cancelling-headphones',
        price: 6499,
        oldPrice: 8999,
        img: 'photo-1505740420928-5e560c06d30e',
        rating: 4.8,
        reviews: 214,
        inStock: true,
        tag: 'Best Seller',
    },
    {
        name: 'Smart Fitness Watch Series 6',
        price: 4299,
        img: 'photo-1523275335684-37898b6baf30',
        rating: 4.6,
        reviews: 167,
        inStock: true,
        tag: 'Best Seller',
    },
    {
        name: 'Classic Leather Sneakers',
        price: 2999,
        oldPrice: 3999,
        img: 'photo-1542291026-7eec264c27ff',
        rating: 4.7,
        reviews: 132,
        inStock: true,
        tag: 'Best Seller',
    },
    {
        name: 'Premium Sunglasses UV400',
        price: 1599,
        img: 'photo-1572635196237-14b3f281503f',
        rating: 4.5,
        reviews: 98,
        inStock: false,
        tag: 'Best Seller',
    },
];

export const newCollectionProducts: ShopProduct[] = [
    {
        name: 'Minimalist Backpack 20L',
        price: 2499,
        img: 'photo-1553062407-98eeb64c6a62',
        rating: 4.4,
        reviews: 41,
        inStock: true,
        tag: 'New',
    },
    {
        name: 'Ceramic Pour-Over Coffee Set',
        price: 1899,
        img: 'photo-1495774856032-8b90bbb32b32',
        rating: 4.9,
        reviews: 23,
        inStock: true,
        tag: 'New',
    },
    {
        name: 'Mechanical Keyboard RGB',
        price: 5499,
        oldPrice: 6299,
        img: 'photo-1587829741301-dc798b83add3',
        rating: 4.7,
        reviews: 36,
        inStock: true,
        tag: 'New',
    },
    {
        name: 'Cotton Oversized T-Shirt',
        price: 899,
        img: 'photo-1521572163474-6864f9cf17ab',
        rating: 4.3,
        reviews: 18,
        inStock: false,
        tag: 'New',
    },
];
