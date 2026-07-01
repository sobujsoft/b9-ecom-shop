<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Eye } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { formatTaka } from '@/lib/shop/currency';
import { dashboard } from '@/routes';
import { index, show } from '@/routes/admin/orders';
import type {
    AdminOrderFilters,
    AdminOrderListItem,
    AdminStatusOption,
} from '@/types/admin';

const props = defineProps<{
    orders: AdminOrderListItem[];
    filters: AdminOrderFilters;
    statusOptions: AdminStatusOption[];
    paymentStatusOptions: AdminStatusOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Orders',
                href: index(),
            },
        ],
    },
});

const selectClass =
    'border-input bg-background ring-offset-background focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px]';

function orderStatusVariant(
    status: AdminOrderListItem['status'],
): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (status) {
        case 'delivered':
            return 'default';
        case 'cancelled':
            return 'destructive';
        case 'pending':
            return 'secondary';
        default:
            return 'outline';
    }
}

function paymentStatusVariant(
    status: AdminOrderListItem['payment_status'],
): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (status) {
        case 'paid':
            return 'default';
        case 'failed':
        case 'cancelled':
            return 'destructive';
        default:
            return 'secondary';
    }
}

function paymentMethodLabel(method: AdminOrderListItem['payment_method']): string {
    return method === 'cod' ? 'Cash on Delivery' : 'SSLCommerz';
}

function formatDate(value: string | null): string {
    if (!value) {
        return '—';
    }

    return new Date(value).toLocaleString();
}
</script>

<template>
    <Head title="Orders" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            title="Orders"
            description="View and manage customer orders"
        />

        <form
            :action="index()"
            method="get"
            class="grid gap-4 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border lg:grid-cols-[1fr_180px_180px_auto]"
        >
            <div class="grid gap-2">
                <Label for="search">Search</Label>
                <Input
                    id="search"
                    name="search"
                    :default-value="props.filters.search"
                    placeholder="Order number, customer, phone, or email"
                />
            </div>

            <div class="grid gap-2">
                <Label for="status">Order status</Label>
                <select
                    id="status"
                    name="status"
                    :class="selectClass"
                    :value="props.filters.status"
                >
                    <option value="">All statuses</option>
                    <option
                        v-for="option in statusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
            </div>

            <div class="grid gap-2">
                <Label for="payment_status">Payment status</Label>
                <select
                    id="payment_status"
                    name="payment_status"
                    :class="selectClass"
                    :value="props.filters.payment_status"
                >
                    <option value="">All payments</option>
                    <option
                        v-for="option in paymentStatusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <Button type="submit">Filter</Button>
                <Button as-child variant="outline">
                    <Link :href="index()">Reset</Link>
                </Button>
            </div>
        </form>

        <div
            class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-sm">
                    <thead class="border-b bg-muted/40 text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium">Order</th>
                            <th class="px-4 py-3 font-medium">Customer</th>
                            <th class="px-4 py-3 font-medium">Items</th>
                            <th class="px-4 py-3 font-medium">Total</th>
                            <th class="px-4 py-3 font-medium">Payment</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Placed</th>
                            <th class="px-4 py-3 text-right font-medium">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="order in orders"
                            :key="order.id"
                            class="border-b last:border-b-0"
                        >
                            <td class="px-4 py-3">
                                <p class="font-medium">
                                    {{ order.order_number }}
                                </p>
                                <p class="text-muted-foreground">
                                    {{ paymentMethodLabel(order.payment_method) }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">
                                    {{ order.customer_name }}
                                </p>
                                <p class="text-muted-foreground">
                                    {{ order.phone }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                {{ order.items_count }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ formatTaka(order.total) }}
                            </td>
                            <td class="px-4 py-3">
                                <Badge
                                    :variant="
                                        paymentStatusVariant(
                                            order.payment_status,
                                        )
                                    "
                                >
                                    {{ order.payment_status }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3">
                                <Badge
                                    :variant="orderStatusVariant(order.status)"
                                >
                                    {{ order.status }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ formatDate(order.placed_at) }}
                            </td>
                            <td class="px-4 py-3">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Button
                                        as-child
                                        variant="outline"
                                        size="sm"
                                    >
                                        <Link :href="show(order.id)">
                                            <Eye class="size-4" />
                                            View
                                        </Link>
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="orders.length === 0">
                            <td
                                colspan="8"
                                class="px-4 py-10 text-center text-muted-foreground"
                            >
                                No orders found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
