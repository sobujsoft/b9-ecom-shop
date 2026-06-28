<?php

use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Inertia\Testing\AssertableInertia as Assert;

// Disable CSRF for all mutation tests in this file
beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

// ─── Page smoke tests ─────────────────────────────────────────────────────────

test('cart page renders with inertia', function () {
    $this->get(route('shop.cart'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('shop/Cart'));
});

test('wishlist page renders with inertia', function () {
    $this->get(route('shop.wishlist'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('shop/Wishlist'));
});

test('checkout page renders with inertia', function () {
    $this->get(route('shop.checkout'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('shop/Checkout'));
});

test('order success page renders with inertia', function () {
    $this->get(route('shop.orders.success'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('shop/OrderSuccess'));
});

// ─── Cart shared props ────────────────────────────────────────────────────────

test('cart shared props are included on every inertia page', function () {
    $this->get(route('shop.cart'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('cart')
            ->has('cart.qty')
            ->has('cart.items')
            ->where('cart.qty', 0)
            ->where('cart.items', [])
        );
});

// ─── Cart store ───────────────────────────────────────────────────────────────

test('can add an active in-stock product to the cart', function () {
    $this->seed(DatabaseSeeder::class);

    $product = Product::where('is_active', true)
        ->where('stock_status', 'in_stock')
        ->first();

    $this->post(route('shop.cart.store'), [
        'product_id' => $product->id,
        'qty' => 2,
    ])->assertRedirect();

    $this->get(route('shop.cart'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('cart.qty', 2)
            ->has('cart.items', 1)
            ->where('cart.items.0.productId', $product->id)
            ->where('cart.items.0.qty', 2)
        );
});

test('adding the same product increments quantity', function () {
    $this->seed(DatabaseSeeder::class);

    $product = Product::where('is_active', true)
        ->where('stock_status', 'in_stock')
        ->first();

    $this->post(route('shop.cart.store'), ['product_id' => $product->id, 'qty' => 1]);
    $this->post(route('shop.cart.store'), ['product_id' => $product->id, 'qty' => 3]);

    $this->get(route('shop.cart'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('cart.qty', 4)
            ->has('cart.items', 1)
            ->where('cart.items.0.qty', 4)
        );
});

test('cannot add a non-existent product to the cart', function () {
    $this->post(route('shop.cart.store'), ['product_id' => 99999])
        ->assertSessionHasErrors('product_id');
});

test('adding an out-of-stock product does not add it to the cart', function () {
    $this->seed(DatabaseSeeder::class);

    $product = Product::where('is_active', true)
        ->where('stock_status', '!=', 'in_stock')
        ->first();

    if (! $product) {
        $this->markTestSkipped('No out-of-stock products in seed data.');
    }

    $this->post(route('shop.cart.store'), ['product_id' => $product->id]);

    $this->get(route('shop.cart'))
        ->assertInertia(fn (Assert $page) => $page->where('cart.qty', 0));
});

// ─── Cart update ──────────────────────────────────────────────────────────────

test('can update cart item quantity', function () {
    $this->seed(DatabaseSeeder::class);

    $product = Product::where('is_active', true)
        ->where('stock_status', 'in_stock')
        ->first();

    $this->post(route('shop.cart.store'), ['product_id' => $product->id, 'qty' => 1]);

    $this->patch(route('shop.cart.update', $product->id), ['qty' => 5])
        ->assertRedirect();

    $this->get(route('shop.cart'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('cart.qty', 5)
            ->where('cart.items.0.qty', 5)
        );
});

// ─── Cart destroy ─────────────────────────────────────────────────────────────

test('can remove a specific item from the cart', function () {
    $this->seed(DatabaseSeeder::class);

    $products = Product::where('is_active', true)
        ->where('stock_status', 'in_stock')
        ->take(2)
        ->get();

    $this->post(route('shop.cart.store'), ['product_id' => $products[0]->id]);
    $this->post(route('shop.cart.store'), ['product_id' => $products[1]->id]);

    $this->delete(route('shop.cart.destroy', $products[0]->id))
        ->assertRedirect();

    $this->get(route('shop.cart'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('cart.items', 1)
            ->where('cart.items.0.productId', $products[1]->id)
        );
});

// ─── Cart clear ───────────────────────────────────────────────────────────────

test('can clear the entire cart', function () {
    $this->seed(DatabaseSeeder::class);

    $product = Product::where('is_active', true)
        ->where('stock_status', 'in_stock')
        ->first();

    $this->post(route('shop.cart.store'), ['product_id' => $product->id, 'qty' => 3]);

    $this->delete(route('shop.cart.clear'))
        ->assertRedirect();

    $this->get(route('shop.cart'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('cart.qty', 0)
            ->where('cart.items', [])
        );
});
