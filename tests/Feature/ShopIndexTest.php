<?php

use Inertia\Testing\AssertableInertia as Assert;

test('shop index page renders with inertia', function () {
    $this->get(route('shop.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Shop')
        );
});
