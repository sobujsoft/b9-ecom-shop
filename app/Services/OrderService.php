<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class OrderService
{
    public function __construct(private readonly CartService $cartService) {}

    /**
     * Calculate the delivery charge for a given district.
     */
    public function deliveryChargeForDistrict(string $district): float
    {
        $delivery = config('shop.delivery');

        return $district === $delivery['dhaka_district']
            ? (float) $delivery['inside_dhaka']
            : (float) $delivery['outside_dhaka'];
    }

    /**
     * Place a Cash on Delivery order from the current cart.
     *
     * @param  array{
     *     customer_name: string,
     *     phone: string,
     *     email: string,
     *     district: string,
     *     area: string,
     *     address: string,
     *     notes?: string|null,
     * }  $shipping
     *
     * @throws ValidationException
     */
    public function placeCodOrder(array $shipping): Order
    {
        $items = $this->cartService->items();

        if ($items === []) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        $outOfStock = collect($items)->first(fn (array $item): bool => ! $item['inStock']);

        if ($outOfStock !== null) {
            throw ValidationException::withMessages([
                'cart' => "{$outOfStock['name']} is no longer available.",
            ]);
        }

        $subtotal = collect($items)->sum(
            fn (array $item): float => $item['price'] * $item['qty'],
        );

        $deliveryCharge = $this->deliveryChargeForDistrict($shipping['district']);
        $total = $subtotal + $deliveryCharge;

        return DB::transaction(function () use ($shipping, $items, $subtotal, $deliveryCharge, $total): Order {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $shipping['customer_name'],
                'phone' => $shipping['phone'],
                'email' => $shipping['email'],
                'district' => $shipping['district'],
                'area' => $shipping['area'],
                'address' => $shipping['address'],
                'notes' => $shipping['notes'] ?? null,
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'total' => $total,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'pending',
                'placed_at' => now(),
            ]);

            foreach ($items as $item) {
                $lineTotal = $item['price'] * $item['qty'];

                $order->items()->create([
                    'product_id' => $item['productId'],
                    'product_name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['qty'],
                    'line_total' => $lineTotal,
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'note' => 'Order placed via Cash on Delivery.',
                'changed_by' => auth()->id(),
            ]);

            $this->cartService->clear();

            return $order;
        });
    }

    /**
     * Generate a unique human-facing order number, e.g. SE-2026-000123.
     */
    private function generateOrderNumber(): string
    {
        $year = now()->format('Y');
        $prefix = "SE-{$year}-";

        for ($attempt = 0; $attempt < 10; $attempt++) {
            $suffix = str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $orderNumber = $prefix.$suffix;

            if (! Order::where('order_number', $orderNumber)->exists()) {
                return $orderNumber;
            }
        }

        throw new RuntimeException('Unable to generate a unique order number.');
    }
}
