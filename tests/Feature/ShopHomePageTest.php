<?php

use Inertia\Testing\AssertableInertia as Assert;

test('shop home page renders with inertia', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Home')
        );
});
