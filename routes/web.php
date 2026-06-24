<?php

use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\OrderSuccessController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', ShopController::class)->name('shop.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('shop.products.show');
Route::get('/cart', CartController::class)->name('shop.cart');
Route::get('/wishlist', WishlistController::class)->name('shop.wishlist');
Route::get('/checkout', CheckoutController::class)->name('shop.checkout');
Route::get('/orders/success', OrderSuccessController::class)->name('shop.orders.success');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
