<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Demo catalog aligned with resources/js/data/shop/catalog.ts.
     *
     * @var list<array{
     *     slug: string,
     *     name: string,
     *     category_slug: string,
     *     price: float,
     *     compare_at_price: float|null,
     *     stock_status: 'in_stock'|'stock_out',
     *     is_best_seller: bool,
     *     is_featured: bool,
     *     sold_count: int,
     *     short_description: string,
     *     description: string,
     *     image: string,
     *     alt_text: string
     * }>
     */
    private const PRODUCTS = [
        [
            'slug' => 'wireless-noise-cancelling-headphones',
            'name' => 'Wireless Noise-Cancelling Headphones',
            'category_slug' => 'electronics',
            'price' => 6499.00,
            'compare_at_price' => 8999.00,
            'stock_status' => 'in_stock',
            'is_best_seller' => true,
            'is_featured' => false,
            'sold_count' => 980,
            'short_description' => 'Premium wireless noise-cancelling headphones with up to 40 hours of battery life and all-day comfort.',
            'description' => "The ShopEase Wireless Noise-Cancelling Headphones are engineered for listeners who refuse to compromise. Advanced hybrid active noise cancellation silences the world around you, while custom 40mm drivers deliver deep, balanced sound across every genre.\n\nDesigned for all-day comfort, the breathable memory-foam ear cushions and lightweight headband let you wear them from your morning commute to late-night sessions. With up to 40 hours of playback and fast charging — 10 minutes gives you 5 hours — you'll rarely reach for the cable.",
            'image' => 'photo-1505740420928-5e560c06d30e',
            'alt_text' => 'Wireless Noise-Cancelling Headphones',
        ],
        [
            'slug' => 'smart-fitness-watch-series-6',
            'name' => 'Smart Fitness Watch Series 6',
            'category_slug' => 'electronics',
            'price' => 4299.00,
            'compare_at_price' => null,
            'stock_status' => 'in_stock',
            'is_best_seller' => true,
            'is_featured' => false,
            'sold_count' => 870,
            'short_description' => 'Track workouts, heart rate, and sleep with a bright always-on display.',
            'description' => 'Stay on top of your health goals with the Smart Fitness Watch Series 6. Built-in GPS, heart-rate monitoring, and sleep tracking help you understand your body. Water-resistant design and a week-long battery make it perfect for everyday wear.',
            'image' => 'photo-1523275335684-37898b6baf30',
            'alt_text' => 'Smart Fitness Watch Series 6',
        ],
        [
            'slug' => 'classic-leather-sneakers',
            'name' => 'Classic Leather Sneakers',
            'category_slug' => 'fashion',
            'price' => 2999.00,
            'compare_at_price' => 3999.00,
            'stock_status' => 'in_stock',
            'is_best_seller' => true,
            'is_featured' => false,
            'sold_count' => 760,
            'short_description' => 'Timeless leather sneakers with cushioned insoles for all-day comfort.',
            'description' => 'These Classic Leather Sneakers combine clean lines with premium materials. A cushioned footbed and durable rubber outsole keep you comfortable from city streets to weekend outings.',
            'image' => 'photo-1542291026-7eec264c27ff',
            'alt_text' => 'Classic Leather Sneakers',
        ],
        [
            'slug' => 'premium-sunglasses-uv400',
            'name' => 'Premium Sunglasses UV400',
            'category_slug' => 'fashion',
            'price' => 1599.00,
            'compare_at_price' => null,
            'stock_status' => 'stock_out',
            'is_best_seller' => true,
            'is_featured' => false,
            'sold_count' => 540,
            'short_description' => 'Polarized lenses with full UV400 protection in a lightweight frame.',
            'description' => 'Shield your eyes in style with Premium Sunglasses UV400. Polarized lenses cut glare while the lightweight acetate frame sits comfortably all day.',
            'image' => 'photo-1572635196237-14b3f281503f',
            'alt_text' => 'Premium Sunglasses UV400',
        ],
        [
            'slug' => 'minimalist-backpack-20l',
            'name' => 'Minimalist Backpack 20L',
            'category_slug' => 'fashion',
            'price' => 2499.00,
            'compare_at_price' => null,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => true,
            'sold_count' => 320,
            'short_description' => 'Slim 20L backpack with padded laptop sleeve and water-resistant fabric.',
            'description' => 'The Minimalist Backpack 20L is built for commuters and students. A padded laptop compartment, organizer pockets, and water-resistant exterior keep your essentials safe and dry.',
            'image' => 'photo-1553062407-98eeb64c6a62',
            'alt_text' => 'Minimalist Backpack 20L',
        ],
        [
            'slug' => 'ceramic-pour-over-coffee-set',
            'name' => 'Ceramic Pour-Over Coffee Set',
            'category_slug' => 'home-living',
            'price' => 1899.00,
            'compare_at_price' => null,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => true,
            'sold_count' => 210,
            'short_description' => 'Handcrafted ceramic dripper and carafe for barista-quality pour-over at home.',
            'description' => 'Brew café-quality coffee at home with this Ceramic Pour-Over Coffee Set. The dripper and carafe are designed for even extraction and easy cleanup.',
            'image' => 'photo-1495774856032-8b90bbb32b32',
            'alt_text' => 'Ceramic Pour-Over Coffee Set',
        ],
        [
            'slug' => 'mechanical-keyboard-rgb',
            'name' => 'Mechanical Keyboard RGB',
            'category_slug' => 'electronics',
            'price' => 5499.00,
            'compare_at_price' => 6299.00,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => true,
            'sold_count' => 430,
            'short_description' => 'Hot-swappable mechanical keyboard with per-key RGB lighting.',
            'description' => 'Type with precision on the Mechanical Keyboard RGB. Hot-swappable switches, durable PBT keycaps, and customizable per-key lighting make it a favorite for work and play.',
            'image' => 'photo-1587829741301-dc798b83add3',
            'alt_text' => 'Mechanical Keyboard RGB',
        ],
        [
            'slug' => 'cotton-oversized-t-shirt',
            'name' => 'Cotton Oversized T-Shirt',
            'category_slug' => 'fashion',
            'price' => 899.00,
            'compare_at_price' => null,
            'stock_status' => 'stock_out',
            'is_best_seller' => false,
            'is_featured' => true,
            'sold_count' => 150,
            'short_description' => 'Soft 100% cotton tee with a relaxed oversized fit.',
            'description' => 'A wardrobe staple in premium combed cotton. The relaxed oversized cut pairs easily with jeans, joggers, or layered under a jacket.',
            'image' => 'photo-1521572163474-6864f9cf17ab',
            'alt_text' => 'Cotton Oversized T-Shirt',
        ],
        [
            'slug' => 'portable-bluetooth-speaker',
            'name' => 'Portable Bluetooth Speaker',
            'category_slug' => 'electronics',
            'price' => 2199.00,
            'compare_at_price' => 2799.00,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => false,
            'sold_count' => 620,
            'short_description' => 'Compact waterproof speaker with 12-hour battery and deep bass.',
            'description' => 'Take your music anywhere with the Portable Bluetooth Speaker. IPX7 waterproofing, 12-hour playtime, and punchy bass make it ideal for outdoor adventures.',
            'image' => 'photo-1608043152269-423dbba4e7e1',
            'alt_text' => 'Portable Bluetooth Speaker',
        ],
        [
            'slug' => 'wireless-earbuds-pro',
            'name' => 'Wireless Earbuds Pro',
            'category_slug' => 'electronics',
            'price' => 3499.00,
            'compare_at_price' => null,
            'stock_status' => 'in_stock',
            'is_best_seller' => true,
            'is_featured' => false,
            'sold_count' => 810,
            'short_description' => 'True wireless earbuds with active noise cancellation and wireless charging case.',
            'description' => 'Wireless Earbuds Pro deliver immersive sound in a pocket-sized package. Active noise cancellation, transparency mode, and a wireless charging case keep you connected on the go.',
            'image' => 'photo-1590658268037-6bf12165a8df',
            'alt_text' => 'Wireless Earbuds Pro',
        ],
        [
            'slug' => 'scented-soy-candle-set',
            'name' => 'Scented Soy Candle Set',
            'category_slug' => 'home-living',
            'price' => 749.00,
            'compare_at_price' => null,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => false,
            'sold_count' => 280,
            'short_description' => 'Set of three hand-poured soy candles in calming scents.',
            'description' => 'Create a cozy atmosphere with this Scented Soy Candle Set. Natural soy wax burns cleanly while lavender, vanilla, and sandalwood notes fill your space.',
            'image' => 'photo-1602874801007-bd458bb1b8b6',
            'alt_text' => 'Scented Soy Candle Set',
        ],
        [
            'slug' => 'matte-lipstick-collection',
            'name' => 'Matte Lipstick Collection',
            'category_slug' => 'beauty',
            'price' => 1299.00,
            'compare_at_price' => 1699.00,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => true,
            'sold_count' => 360,
            'short_description' => 'Long-wear matte lipsticks in six flattering everyday shades.',
            'description' => 'The Matte Lipstick Collection offers rich pigment and a comfortable matte finish. Six versatile shades from nude to berry suit every skin tone.',
            'image' => 'photo-1586495777744-4413f21062fa',
            'alt_text' => 'Matte Lipstick Collection',
        ],
        [
            'slug' => 'yoga-mat-non-slip',
            'name' => 'Yoga Mat Non-Slip',
            'category_slug' => 'sports',
            'price' => 1450.00,
            'compare_at_price' => null,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => false,
            'sold_count' => 290,
            'short_description' => 'Extra-thick non-slip yoga mat with carrying strap.',
            'description' => 'Practice with confidence on the Yoga Mat Non-Slip. A textured surface prevents slipping while extra cushioning protects your joints during floor work.',
            'image' => 'photo-1601925260368-ae2f83cf8b7f',
            'alt_text' => 'Yoga Mat Non-Slip',
        ],
        [
            'slug' => 'stainless-steel-water-bottle',
            'name' => 'Stainless Steel Water Bottle',
            'category_slug' => 'sports',
            'price' => 999.00,
            'compare_at_price' => null,
            'stock_status' => 'stock_out',
            'is_best_seller' => false,
            'is_featured' => false,
            'sold_count' => 470,
            'short_description' => 'Insulated stainless steel bottle keeps drinks cold for 24 hours.',
            'description' => 'Stay hydrated with the Stainless Steel Water Bottle. Double-wall insulation keeps beverages cold for 24 hours or hot for 12, with a leak-proof lid for travel.',
            'image' => 'photo-1602143407151-7111542de6e8',
            'alt_text' => 'Stainless Steel Water Bottle',
        ],
    ];

    /**
     * Seed demo products and their primary images.
     */
    public function run(): void
    {
        $categories = Category::query()
            ->pluck('id', 'slug');

        foreach (self::PRODUCTS as $data) {
            $categoryId = $categories[$data['category_slug']] ?? null;

            if ($categoryId === null) {
                continue;
            }

            $product = Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $categoryId,
                    'name' => $data['name'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'compare_at_price' => $data['compare_at_price'],
                    'stock_status' => $data['stock_status'],
                    'is_best_seller' => $data['is_best_seller'],
                    'is_featured' => $data['is_featured'],
                    'sold_count' => $data['sold_count'],
                    'is_active' => true,
                ],
            );

            ProductImage::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'is_primary' => true,
                ],
                [
                    'image_path' => $data['image'],
                    'alt_text' => $data['alt_text'],
                    'sort_order' => 0,
                ],
            );
        }
    }
}
