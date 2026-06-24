import type { ShopWishlistItem } from '@/types/shop';

export const staticWishlistItems: ShopWishlistItem[] = [
    {
        name: 'Wireless Noise-Cancelling Headphones',
        slug: 'wireless-noise-cancelling-headphones',
        price: 6499,
        oldPrice: 8999,
        img: 'photo-1505740420928-5e560c06d30e',
        rating: 4.8,
        reviews: 214,
        inStock: true,
    },
    {
        name: 'Smart Fitness Watch Series 6',
        slug: 'smart-fitness-watch-series-6',
        price: 4299,
        img: 'photo-1523275335684-37898b6baf30',
        rating: 4.6,
        reviews: 167,
        inStock: true,
    },
    {
        name: 'Premium Sunglasses UV400',
        slug: 'premium-sunglasses-uv400',
        price: 1599,
        img: 'photo-1572635196237-14b3f281503f',
        rating: 4.5,
        reviews: 98,
        inStock: false,
    },
    {
        name: 'Mechanical Keyboard RGB',
        slug: 'mechanical-keyboard-rgb',
        price: 5499,
        oldPrice: 6299,
        img: 'photo-1587829741301-dc798b83add3',
        rating: 4.7,
        reviews: 36,
        inStock: true,
    },
    {
        name: 'Ceramic Pour-Over Coffee Set',
        slug: 'ceramic-pour-over-coffee-set',
        price: 1899,
        img: 'photo-1495774856032-8b90bbb32b32',
        rating: 4.9,
        reviews: 23,
        inStock: true,
    },
];

export const bangladeshDistricts = [
    'Dhaka',
    'Chattogram',
    'Sylhet',
    'Khulna',
    'Rajshahi',
    'Barishal',
    'Rangpur',
    'Mymensingh',
] as const;

export const deliveryCharges = {
    dhaka: 60,
    outside: 120,
} as const;
