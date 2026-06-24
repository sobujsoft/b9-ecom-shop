<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * @var list<array{name: string, slug: string, image: string, description: string, sort_order: int}>
     */
    private const CATEGORIES = [
        [
            'name' => 'Electronics',
            'slug' => 'electronics',
            'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=70',
            'description' => 'Gadgets, audio, wearables, and everyday tech.',
            'sort_order' => 1,
        ],
        [
            'name' => 'Fashion',
            'slug' => 'fashion',
            'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=400&q=70',
            'description' => 'Clothing, footwear, and accessories.',
            'sort_order' => 2,
        ],
        [
            'name' => 'Home & Living',
            'slug' => 'home-living',
            'image' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=400&q=70',
            'description' => 'Decor, kitchen, and home essentials.',
            'sort_order' => 3,
        ],
        [
            'name' => 'Beauty',
            'slug' => 'beauty',
            'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=70',
            'description' => 'Skincare, makeup, and personal care.',
            'sort_order' => 4,
        ],
        [
            'name' => 'Sports',
            'slug' => 'sports',
            'image' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=400&q=70',
            'description' => 'Fitness gear and outdoor equipment.',
            'sort_order' => 5,
        ],
        [
            'name' => 'Books',
            'slug' => 'books',
            'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70',
            'description' => 'Fiction, non-fiction, and study materials.',
            'sort_order' => 6,
        ],
    ];

    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
