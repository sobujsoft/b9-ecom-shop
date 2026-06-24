<?php

use Inertia\Testing\AssertableInertia as Assert;

test('product show page renders with inertia', function () {
    $this->get(route('shop.products.show', 'wireless-noise-cancelling-headphones'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/ProductShow')
            ->where('slug', 'wireless-noise-cancelling-headphones')
        );
});

test('product show page returns not found for unknown slug', function () {
    $this->get(route('shop.products.show', 'unknown-product'))
        ->assertNotFound();
});
