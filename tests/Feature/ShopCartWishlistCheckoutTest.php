<?php

use Inertia\Testing\AssertableInertia as Assert;

test('cart page renders with inertia', function () {
    $this->get(route('shop.cart'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Cart')
        );
});

test('wishlist page renders with inertia', function () {
    $this->get(route('shop.wishlist'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Wishlist')
        );
});

test('checkout page renders with inertia', function () {
    $this->get(route('shop.checkout'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Checkout')
        );
});

test('order success page renders with inertia', function () {
    $this->get(route('shop.orders.success'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/OrderSuccess')
        );
});
