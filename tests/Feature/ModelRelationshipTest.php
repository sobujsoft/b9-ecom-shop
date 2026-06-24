<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

it('defines category relationships', function () {
    $category = new Category;

    expect($category->products())->toBeInstanceOf(HasMany::class);
});

it('defines product relationships', function () {
    $product = new Product;

    expect($product->category())->toBeInstanceOf(BelongsTo::class)
        ->and($product->images())->toBeInstanceOf(HasMany::class)
        ->and($product->orderItems())->toBeInstanceOf(HasMany::class)
        ->and($product->reviews())->toBeInstanceOf(HasMany::class)
        ->and($product->cartItems())->toBeInstanceOf(HasMany::class)
        ->and($product->wishlists())->toBeInstanceOf(HasMany::class);
});

it('defines product image relationships', function () {
    $image = new ProductImage;

    expect($image->product())->toBeInstanceOf(BelongsTo::class);
});

it('defines cart relationships', function () {
    $cart = new Cart;

    expect($cart->user())->toBeInstanceOf(BelongsTo::class)
        ->and($cart->items())->toBeInstanceOf(HasMany::class);
});

it('defines cart item relationships', function () {
    $item = new CartItem;

    expect($item->cart())->toBeInstanceOf(BelongsTo::class)
        ->and($item->product())->toBeInstanceOf(BelongsTo::class);
});

it('defines order relationships', function () {
    $order = new Order;

    expect($order->user())->toBeInstanceOf(BelongsTo::class)
        ->and($order->items())->toBeInstanceOf(HasMany::class)
        ->and($order->invoice())->toBeInstanceOf(HasOne::class)
        ->and($order->payments())->toBeInstanceOf(HasMany::class)
        ->and($order->statusHistories())->toBeInstanceOf(HasMany::class)
        ->and($order->reviews())->toBeInstanceOf(HasMany::class);
});

it('defines order item relationships', function () {
    $item = new OrderItem;

    expect($item->order())->toBeInstanceOf(BelongsTo::class)
        ->and($item->product())->toBeInstanceOf(BelongsTo::class);
});

it('defines invoice relationships', function () {
    $invoice = new Invoice;

    expect($invoice->order())->toBeInstanceOf(BelongsTo::class);
});

it('defines payment relationships', function () {
    $payment = new Payment;

    expect($payment->order())->toBeInstanceOf(BelongsTo::class);
});

it('defines order status history relationships', function () {
    $history = new OrderStatusHistory;

    expect($history->order())->toBeInstanceOf(BelongsTo::class)
        ->and($history->changedBy())->toBeInstanceOf(BelongsTo::class);
});

it('defines review relationships', function () {
    $review = new Review;

    expect($review->product())->toBeInstanceOf(BelongsTo::class)
        ->and($review->user())->toBeInstanceOf(BelongsTo::class)
        ->and($review->order())->toBeInstanceOf(BelongsTo::class);
});

it('defines wishlist relationships', function () {
    $wishlist = new Wishlist;

    expect($wishlist->user())->toBeInstanceOf(BelongsTo::class)
        ->and($wishlist->product())->toBeInstanceOf(BelongsTo::class);
});

it('defines user relationships', function () {
    $user = new User;

    expect($user->orders())->toBeInstanceOf(HasMany::class)
        ->and($user->reviews())->toBeInstanceOf(HasMany::class)
        ->and($user->wishlists())->toBeInstanceOf(HasMany::class)
        ->and($user->cart())->toBeInstanceOf(HasOne::class)
        ->and($user->statusChanges())->toBeInstanceOf(HasMany::class);
});
