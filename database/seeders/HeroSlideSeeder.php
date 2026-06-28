<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * @var list<array{image: string, link: string|null, sort_order: int}>
     */
    private const SLIDES = [
        [
            'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1920&q=70',
            'link' => '/shop',
            'sort_order' => 1,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1920&q=70',
            'link' => '/shop?sort=best',
            'sort_order' => 2,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1920&q=70',
            'link' => '/shop?category=fashion',
            'sort_order' => 3,
        ],
    ];

    /**
     * Seed the storefront hero carousel slides.
     */
    public function run(): void
    {
        foreach (self::SLIDES as $slide) {
            HeroSlide::query()->updateOrCreate(
                ['image' => $slide['image']],
                $slide,
            );
        }
    }
}
