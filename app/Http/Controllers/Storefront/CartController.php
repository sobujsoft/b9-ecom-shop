<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    /**
     * Display the shopping cart page.
     */
    public function index(): Response
    {
        return Inertia::render('shop/Cart');
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::where('id', $data['product_id'])
            ->where('is_active', true)
            ->where('stock_status', 'in_stock')
            ->first();

        if (! $product) {
            return back()->with('error', 'This product is not available.');
        }

        $this->cartService->add($data['product_id'], $data['qty'] ?? 1);

        return back();
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $this->cartService->update($productId, $data['qty']);

        return back();
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(int $productId): RedirectResponse
    {
        $this->cartService->remove($productId);

        return back();
    }

    /**
     * Remove all items from the cart.
     */
    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return back();
    }
}
