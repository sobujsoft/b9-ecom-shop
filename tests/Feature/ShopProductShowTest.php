<?php

use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('product show page renders with inertia', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.products.show', 'wireless-noise-cancelling-headphones'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/ProductShow')
            ->has('product')
            ->has('relatedProducts')
            ->where('product.slug', 'wireless-noise-cancelling-headphones')
            ->where('product.name', 'Wireless Noise-Cancelling Headphones')
            ->where('product.category', 'Electronics')
            ->where('product.inStock', true)
            ->where('product.tag', 'Best Seller')
            ->has('product.images', 1)
            ->has('product.reviewList', 3)
            ->has('relatedProducts', 4)
        );
});

test('product show page works for any seeded product slug', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.products.show', 'classic-leather-sneakers'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/ProductShow')
            ->where('product.slug', 'classic-leather-sneakers')
            ->where('product.category', 'Fashion')
        );
});

test('product show page returns not found for unknown slug', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.products.show', 'unknown-product'))
        ->assertNotFound();
});

test('product show page returns not found for inactive product', function () {
    $this->seed(DatabaseSeeder::class);

    $product = Product::query()
        ->where('slug', 'wireless-noise-cancelling-headphones')
        ->firstOrFail();

    $product->update(['is_active' => false]);

    $this->get(route('shop.products.show', 'wireless-noise-cancelling-headphones'))
        ->assertNotFound();
});
