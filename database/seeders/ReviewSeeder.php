<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * @var list<array{product_slug: string, user_email: string, rating: int, comment: string, is_approved: bool}>
     */
    private const REVIEWS = [
        [
            'product_slug' => 'wireless-noise-cancelling-headphones',
            'user_email' => 'rina@example.com',
            'rating' => 5,
            'comment' => 'Sound quality is amazing and the noise cancellation actually works on the bus. Battery lasts me almost a week. Highly recommended!',
            'is_approved' => true,
        ],
        [
            'product_slug' => 'wireless-noise-cancelling-headphones',
            'user_email' => 'karim@example.com',
            'rating' => 5,
            'comment' => 'Delivered fast inside Dhaka, paid cash on delivery. Build quality feels premium for the price.',
            'is_approved' => true,
        ],
        [
            'product_slug' => 'wireless-noise-cancelling-headphones',
            'user_email' => 'sadia@example.com',
            'rating' => 4,
            'comment' => 'Very comfortable for long use. Only wish the case was a bit smaller, but overall great value.',
            'is_approved' => true,
        ],
        [
            'product_slug' => 'smart-fitness-watch-series-6',
            'user_email' => 'rina@example.com',
            'rating' => 5,
            'comment' => 'Accurate heart-rate tracking and the battery easily lasts five days.',
            'is_approved' => true,
        ],
        [
            'product_slug' => 'classic-leather-sneakers',
            'user_email' => 'karim@example.com',
            'rating' => 4,
            'comment' => 'Great everyday sneakers. True to size and very comfortable.',
            'is_approved' => true,
        ],
        [
            'product_slug' => 'wireless-earbuds-pro',
            'user_email' => 'sadia@example.com',
            'rating' => 5,
            'comment' => 'ANC is surprisingly good for the price. Case charges quickly too.',
            'is_approved' => true,
        ],
    ];

    /**
     * Seed approved product reviews for the demo catalog.
     */
    public function run(): void
    {
        $products = Product::query()->pluck('id', 'slug');
        $users = User::query()->pluck('id', 'email');

        foreach (self::REVIEWS as $review) {
            $productId = $products[$review['product_slug']] ?? null;
            $userId = $users[$review['user_email']] ?? null;

            if ($productId === null || $userId === null) {
                continue;
            }

            Review::query()->updateOrCreate(
                [
                    'product_id' => $productId,
                    'user_id' => $userId,
                ],
                [
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                    'is_approved' => $review['is_approved'],
                    'approved_at' => $review['is_approved'] ? now() : null,
                ],
            );
        }
    }
}
