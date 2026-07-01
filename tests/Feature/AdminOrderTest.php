<?php

use App\Models\Order;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot access admin orders', function () {
    $this->get(route('admin.orders.index'))
        ->assertRedirect(route('login'));
});

test('customers cannot access admin orders', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.orders.index'))
        ->assertForbidden();
});

test('admins can view the orders index', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.orders.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/orders/Index')
            ->has('orders', 1)
            ->has('orders.0', fn (Assert $orderPage) => $orderPage
                ->where('id', $order->id)
                ->where('order_number', $order->order_number)
                ->has('customer_name')
                ->has('phone')
                ->has('email')
                ->has('total')
                ->has('items_count')
                ->has('payment_method')
                ->has('payment_status')
                ->has('status')
                ->etc()
            )
            ->has('filters')
            ->has('statusOptions')
            ->has('paymentStatusOptions')
        );
});

test('admins can filter orders by status', function () {
    $admin = User::factory()->admin()->create();
    $pendingOrder = Order::factory()->create(['status' => 'pending']);
    Order::factory()->processing()->create();

    $this->actingAs($admin)
        ->get(route('admin.orders.index', ['status' => 'pending']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/orders/Index')
            ->has('orders', 1)
            ->where('orders.0.id', $pendingOrder->id)
            ->where('filters.status', 'pending')
        );
});

test('admins can search orders', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create([
        'order_number' => 'SE-2026-000777',
        'customer_name' => 'Searchable Customer',
    ]);
    Order::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.orders.index', ['search' => '000777']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/orders/Index')
            ->has('orders', 1)
            ->where('orders.0.id', $order->id)
            ->where('filters.search', '000777')
        );
});

test('admins can view an order', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/orders/Show')
            ->where('order.id', $order->id)
            ->where('order.order_number', $order->order_number)
            ->has('order.items', 1)
            ->has('order.status_histories', 1)
            ->has('statusOptions')
            ->has('paymentStatusOptions')
        );
});

test('admins can update an order status', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create([
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.orders.update', $order), [
            'status' => 'processing',
            'payment_status' => 'pending',
            'note' => 'Packing started.',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    $order->refresh();

    expect($order->status)->toBe('processing')
        ->and($order->payment_status)->toBe('pending');

    $this->assertDatabaseHas('order_status_histories', [
        'order_id' => $order->id,
        'status' => 'processing',
        'note' => 'Packing started.',
        'changed_by' => $admin->id,
    ]);
});

test('admins can update payment status without changing order status', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create([
        'status' => 'processing',
        'payment_status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.orders.update', $order), [
            'status' => 'processing',
            'payment_status' => 'paid',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    $order->refresh();

    expect($order->payment_status)->toBe('paid');

    $this->assertDatabaseHas('order_status_histories', [
        'order_id' => $order->id,
        'status' => 'processing',
        'changed_by' => $admin->id,
    ]);
});

test('order update requires valid statuses', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create();

    $this->actingAs($admin)
        ->from(route('admin.orders.show', $order))
        ->put(route('admin.orders.update', $order), [
            'status' => 'invalid-status',
            'payment_status' => 'pending',
        ])
        ->assertRedirect(route('admin.orders.show', $order))
        ->assertSessionHasErrors(['status']);
});

test('unchanged order update without note does not create history', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create([
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);

    $historyCount = $order->statusHistories()->count();

    $this->actingAs($admin)
        ->put(route('admin.orders.update', $order), [
            'status' => 'pending',
            'payment_status' => 'pending',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    expect($order->statusHistories()->count())->toBe($historyCount);
});
