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

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('shop.cart');
Route::post('/cart', [CartController::class, 'store'])->name('shop.cart.store');
Route::patch('/cart/{productId}', [CartController::class, 'update'])->name('shop.cart.update');
Route::delete('/cart', [CartController::class, 'clear'])->name('shop.cart.clear');
Route::delete('/cart/{productId}', [CartController::class, 'destroy'])->name('shop.cart.destroy');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('shop.wishlist');
Route::middleware('auth')->group(function () {
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('shop.wishlist.store');
    Route::delete('/wishlist', [WishlistController::class, 'clear'])->name('shop.wishlist.clear');
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('shop.wishlist.destroy');
});
Route::get('/checkout', [CheckoutController::class, 'index'])->name('shop.checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('shop.checkout.store');
Route::get('/orders/success', OrderSuccessController::class)->name('shop.orders.success');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
