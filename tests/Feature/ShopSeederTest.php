<?php

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

test('database seeder populates demo shop data', function () {
    $this->seed(DatabaseSeeder::class);

    expect(User::query()->count())->toBe(5)
        ->and(User::query()->where('role', 'admin')->count())->toBe(1)
        ->and(Category::query()->count())->toBe(6)
        ->and(HeroSlide::query()->count())->toBe(3)
        ->and(Product::query()->count())->toBe(14)
        ->and(Review::query()->where('is_approved', true)->count())->toBe(6);

    $headphones = Product::query()
        ->where('slug', 'wireless-noise-cancelling-headphones')
        ->first();

    expect($headphones)->not->toBeNull()
        ->and($headphones->is_best_seller)->toBeTrue()
        ->and($headphones->sold_count)->toBe(980)
        ->and($headphones->images()->where('is_primary', true)->exists())->toBeTrue();
});

test('database seeder is idempotent', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(Product::query()->count())->toBe(14)
        ->and(Category::query()->count())->toBe(6);
});
