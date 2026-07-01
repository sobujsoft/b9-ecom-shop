<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\OrderValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    use OrderValidationRules;

    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    /**
     * Display a listing of orders.
     */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');
        $paymentStatus = (string) $request->query('payment_status', '');

        $orders = Order::query()
            ->withCount('items')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($status, self::orderStatuses(), true),
                fn ($query) => $query->where('status', $status),
            )
            ->when(
                in_array($paymentStatus, self::paymentStatuses(), true),
                fn ($query) => $query->where('payment_status', $paymentStatus),
            )
            ->orderByDesc('placed_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Order $order): array => $this->listPayload($order));

        return Inertia::render('admin/orders/Index', [
            'orders' => $orders,
            'filters' => [
                'search' => $search,
                'status' => in_array($status, self::orderStatuses(), true) ? $status : '',
                'payment_status' => in_array($paymentStatus, self::paymentStatuses(), true) ? $paymentStatus : '',
            ],
            'statusOptions' => $this->statusOptions(self::orderStatuses()),
            'paymentStatusOptions' => $this->statusOptions(self::paymentStatuses()),
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): Response
    {
        $order->load([
            'items.product',
            'statusHistories.changedBy',
            'user',
        ]);

        return Inertia::render('admin/orders/Show', [
            'order' => $this->detailPayload($order),
            'statusOptions' => $this->statusOptions(self::orderStatuses()),
            'paymentStatusOptions' => $this->statusOptions(self::paymentStatuses()),
        ]);
    }

    /**
     * Update the specified order in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $this->orderService->updateFromAdmin(
            $order,
            $request->validated(),
            $request->user()?->id,
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order updated.')]);

        return to_route('admin.orders.show', $order);
    }

    /**
     * @return array{
     *     id: int,
     *     order_number: string,
     *     customer_name: string,
     *     phone: string,
     *     email: string,
     *     total: float,
     *     items_count: int,
     *     payment_method: string,
     *     payment_status: string,
     *     status: string,
     *     placed_at: string|null,
     *     created_at: string
     * }
     */
    private function listPayload(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'phone' => $order->phone,
            'email' => $order->email,
            'total' => (float) $order->total,
            'items_count' => (int) ($order->items_count ?? 0),
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
            'placed_at' => $order->placed_at?->toIso8601String(),
            'created_at' => $order->created_at?->toIso8601String() ?? '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function detailPayload(Order $order): array
    {
        return [
            ...$this->listPayload($order),
            'district' => $order->district,
            'area' => $order->area,
            'address' => $order->address,
            'notes' => $order->notes,
            'subtotal' => (float) $order->subtotal,
            'delivery_charge' => (float) $order->delivery_charge,
            'customer' => $order->user ? [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email,
            ] : null,
            'items' => $order->items
                ->map(fn ($item): array => [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'unit_price' => (float) $item->unit_price,
                    'quantity' => (int) $item->quantity,
                    'line_total' => (float) $item->line_total,
                ])
                ->values()
                ->all(),
            'status_histories' => $order->statusHistories
                ->sortByDesc('created_at')
                ->values()
                ->map(fn ($history): array => [
                    'id' => $history->id,
                    'status' => $history->status,
                    'note' => $history->note,
                    'changed_by' => $history->changedBy ? [
                        'id' => $history->changedBy->id,
                        'name' => $history->changedBy->name,
                    ] : null,
                    'created_at' => $history->created_at?->toIso8601String() ?? '',
                ])
                ->all(),
            'updated_at' => $order->updated_at?->toIso8601String() ?? '',
        ];
    }

    /**
     * @param  list<string>  $values
     * @return list<array{value: string, label: string}>
     */
    private function statusOptions(array $values): array
    {
        return collect($values)
            ->map(fn (string $value): array => [
                'value' => $value,
                'label' => ucfirst(str_replace('_', ' ', $value)),
            ])
            ->values()
            ->all();
    }
}
