import type { ShopProduct, ShopProductDetail } from '@/types/shop';

export const relatedProducts: ShopProduct[] = [
    {
        name: 'Smart Fitness Watch Series 6',
        slug: 'smart-fitness-watch-series-6',
        price: 4299,
        img: 'photo-1523275335684-37898b6baf30',
        rating: 4.6,
        reviews: 167,
        inStock: true,
        tag: 'Best Seller',
    },
    {
        name: 'Portable Bluetooth Speaker',
        slug: 'portable-bluetooth-speaker',
        price: 2199,
        oldPrice: 2799,
        img: 'photo-1608043152269-423dbba4e7e1',
        rating: 4.5,
        reviews: 89,
        inStock: true,
    },
    {
        name: 'Wireless Earbuds Pro',
        slug: 'wireless-earbuds-pro',
        price: 3499,
        img: 'photo-1590658268037-6bf12165a8df',
        rating: 4.7,
        reviews: 142,
        inStock: false,
    },
    {
        name: 'Premium Sunglasses UV400',
        slug: 'premium-sunglasses-uv400',
        price: 1599,
        img: 'photo-1572635196237-14b3f281503f',
        rating: 4.5,
        reviews: 98,
        inStock: true,
        tag: 'New',
    },
];

export const productDetails: Record<string, ShopProductDetail> = {
    'wireless-noise-cancelling-headphones': {
        slug: 'wireless-noise-cancelling-headphones',
        name: 'Wireless Noise-Cancelling Headphones',
        category: 'Electronics',
        categoryHref: '/#categories',
        price: 6499,
        oldPrice: 8999,
        img: 'photo-1505740420928-5e560c06d30e',
        rating: 4.8,
        reviews: 214,
        inStock: true,
        tag: 'Best Seller',
        summary:
            'Premium wireless noise-cancelling headphones with up to 40 hours of battery life and all-day comfort. Full details in the description below.',
        description: [
            'The ShopEase Wireless Noise-Cancelling Headphones are engineered for listeners who refuse to compromise. Advanced hybrid active noise cancellation silences the world around you, while custom 40mm drivers deliver deep, balanced sound across every genre.',
            'Designed for all-day comfort, the breathable memory-foam ear cushions and lightweight headband let you wear them from your morning commute to late-night sessions. With up to 40 hours of playback and fast charging — 10 minutes gives you 5 hours — you\'ll rarely reach for the cable.',
        ],
        features: [
            'Hybrid Active Noise Cancellation (ANC) with transparency mode',
            'Up to 40 hours battery life; USB-C fast charging',
            'Bluetooth 5.3 with multipoint pairing',
            'Built-in microphone with environmental noise reduction for calls',
            'Foldable design with travel case included',
        ],
        images: [
            {
                full: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=75',
                thumb: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=200&q=70',
            },
            {
                full: 'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=900&q=75',
                thumb: 'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=200&q=70',
            },
            {
                full: 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=900&q=75',
                thumb: 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=200&q=70',
            },
            {
                full: 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=900&q=75',
                thumb: 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=200&q=70',
            },
            {
                full: 'https://images.unsplash.com/photo-1577174881658-0f30ed549adc?auto=format&fit=crop&w=900&q=75',
                thumb: 'https://images.unsplash.com/photo-1577174881658-0f30ed549adc?auto=format&fit=crop&w=200&q=70',
            },
        ],
        ratingBreakdown: [
            { stars: 5, percent: 78 },
            { stars: 4, percent: 15 },
            { stars: 3, percent: 5 },
            { stars: 2, percent: 1 },
            { stars: 1, percent: 1 },
        ],
        reviewList: [
            {
                name: 'Rina A.',
                rating: 5,
                date: '12 Jun 2026',
                verified: true,
                text: 'Sound quality is amazing and the noise cancellation actually works on the bus. Battery lasts me almost a week. Highly recommended!',
            },
            {
                name: 'Karim H.',
                rating: 5,
                date: '5 Jun 2026',
                verified: true,
                text: 'Delivered fast inside Dhaka, paid cash on delivery. Build quality feels premium for the price.',
            },
            {
                name: 'Sadia R.',
                rating: 4,
                date: '28 May 2026',
                verified: false,
                text: 'Very comfortable for long use. Only wish the case was a bit smaller, but overall great value.',
            },
        ],
    },
};
