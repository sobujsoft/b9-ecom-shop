<?php

use Database\Seeders\DatabaseSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('shop home page renders with inertia', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Home')
            ->has('heroSlides', 3)
            ->has('categories', 6)
            ->has('bestSellingProducts', 4)
            ->has('newCollectionProducts', 4)
            ->where('bestSellingProducts.0.slug', 'wireless-noise-cancelling-headphones')
            ->where('newCollectionProducts.0.slug', 'minimalist-backpack-20l')
        );
});
