<?php

use Database\Seeders\DatabaseSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('shop index page renders with inertia', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Shop')
            ->has('products', 8)       // first page (PER_PAGE = 8)
            ->has('categories', 6)
            ->has('meta')
            ->has('filters')
            ->where('meta.total', 14)
            ->where('meta.currentPage', 1)
            ->where('meta.lastPage', 2)
            ->where('meta.perPage', 8)
            ->where('filters.categories', [])
            ->where('filters.price', 'all')
            ->where('filters.inStock', false)
            ->where('filters.sort', 'newest')
            ->where('filters.search', '')
            ->where('filters.page', 1)
            ->where('categories.0.name', 'Electronics')
        );
});

test('shop index page includes catalog product fields', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('products.0', fn (Assert $product) => $product
                ->has('id')
                ->has('name')
                ->has('slug')
                ->has('price')
                ->has('oldPrice')
                ->has('img')
                ->has('rating')
                ->has('reviews')
                ->has('inStock')
                ->has('category')
                ->has('tag')
                ->has('sold')
            )
        );
});

test('shop index page 2 returns remaining products', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index', ['page' => 2]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('products', 6)
            ->where('meta.currentPage', 2)
            ->where('meta.total', 14)
        );
});

test('shop index filters by single category slug', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index', ['categories' => ['electronics']]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.categories', ['electronics'])
            ->where('meta.total', fn (int $total) => $total > 0)
        );
});

test('shop index filters by multiple categories', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index', ['categories' => ['electronics', 'fashion']]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.categories', ['electronics', 'fashion'])
        );
});

test('shop index filters by in stock only', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index', ['in_stock' => '1']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.inStock', true)
        );
});

test('shop index searches by keyword', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index', ['search' => 'shirt']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.search', 'shirt')
        );
});

test('shop index sorts by price ascending', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('shop.index', ['sort' => 'price-asc']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.sort', 'price-asc')
        );
});
