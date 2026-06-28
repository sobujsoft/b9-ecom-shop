<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    public function __construct(private readonly WishlistService $wishlistService) {}

    /**
     * Display the wishlist page.
     */
    public function index(): Response
    {
        return Inertia::render('shop/Wishlist');
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $product = Product::where('id', $data['product_id'])
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return back()->with('error', 'This product is not available.');
        }

        $this->wishlistService->add($data['product_id']);

        return back();
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy(int $productId): RedirectResponse
    {
        $this->wishlistService->remove($productId);

        return back();
    }

    /**
     * Remove all items from the wishlist.
     */
    public function clear(): RedirectResponse
    {
        $this->wishlistService->clear();

        return back();
    }
}
