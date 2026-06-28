<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
    ) {}

    /**
     * Display the checkout page.
     */
    public function index(): Response|RedirectResponse
    {
        if ($this->cartService->totalQty() === 0) {
            return redirect()
                ->route('shop.cart')
                ->with('error', 'Your cart is empty. Add items before checkout.');
        }

        $delivery = config('shop.delivery');

        return Inertia::render('shop/Checkout', [
            'districts' => config('shop.districts'),
            'deliveryCharges' => [
                'insideDhaka' => (float) $delivery['inside_dhaka'],
                'outsideDhaka' => (float) $delivery['outside_dhaka'],
                'dhakaDistrict' => $delivery['dhaka_district'],
            ],
        ]);
    }

    /**
     * Place a Cash on Delivery order.
     */
    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $order = $this->orderService->placeCodOrder($request->validated());

        return redirect()
            ->route('shop.orders.success')
            ->with('order', [
                'orderNumber' => $order->order_number,
                'total' => (float) $order->total,
                'paymentLabel' => 'Cash on Delivery',
            ]);
    }
}
