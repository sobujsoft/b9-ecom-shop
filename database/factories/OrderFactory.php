<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 500, 5000);
        $deliveryCharge = fake()->randomElement([60.0, 120.0]);

        return [
            'order_number' => $this->generateOrderNumber(),
            'user_id' => null,
            'customer_name' => fake()->name(),
            'phone' => '01'.fake()->numerify('#########'),
            'email' => fake()->safeEmail(),
            'district' => fake()->randomElement(['Dhaka', 'Chattogram', 'Sylhet']),
            'area' => fake()->city(),
            'address' => fake()->streetAddress(),
            'notes' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'total' => $subtotal + $deliveryCharge,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
            'placed_at' => now(),
        ];
    }

    /**
     * Attach line items and an initial status history entry.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Order $order): void {
            if ($order->items()->exists()) {
                return;
            }

            $product = Product::factory()->create();
            $quantity = fake()->numberBetween(1, 3);
            $lineTotal = (float) $product->price * $quantity;

            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ]);

            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'status' => $order->status,
                'note' => 'Order placed via Cash on Delivery.',
                'changed_by' => $order->user_id,
                'created_at' => $order->placed_at ?? now(),
            ]);
        });
    }

    public function processing(): static
    {
        return $this->state(fn (): array => ['status' => 'processing']);
    }

    public function delivered(): static
    {
        return $this->state(fn (): array => [
            'status' => 'delivered',
            'payment_status' => 'paid',
        ]);
    }

    private function generateOrderNumber(): string
    {
        $year = now()->format('Y');

        return 'SE-'.$year.'-'.str_pad((string) fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}
