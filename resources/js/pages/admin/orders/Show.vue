<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import {
    CheckCircle,
    Clock,
    Download,
    Mail,
    MapPin,
    Package,
    Phone,
    Printer,
    RotateCcw,
    Truck,
    User,
    XCircle,
} from '@lucide/vue';
import OrderController from '@/actions/App/Http/Controllers/Admin/OrderController';
import OrderStatusForm from '@/components/admin/OrderStatusForm.vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { formatTaka } from '@/lib/shop/currency';
import { dashboard } from '@/routes';
import { index, show } from '@/routes/admin/orders';
import type {
    AdminOrder,
    AdminStatusOption,
    OrderStatus,
    OrderUpdateFormData,
    PaymentStatus,
} from '@/types/admin';

const props = defineProps<{
    order: AdminOrder;
    statusOptions: AdminStatusOption[];
    paymentStatusOptions: AdminStatusOption[];
}>();

setLayoutProps({
    breadcrumbs: [
        { title: 'Dashboard', href: dashboard() },
        { title: 'Orders', href: index() },
        { title: props.order.order_number, href: show(props.order.id) },
    ],
});

const form = useForm<OrderUpdateFormData>({
    status: props.order.status,
    payment_status: props.order.payment_status,
    note: '',
});

function submit(): void {
    form.put(OrderController.update.url(props.order.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('note'),
    });
}

const ORDER_STEPS: OrderStatus[] = [
    'pending',
    'processing',
    'shipped',
    'delivered',
];

type StepColors = {
    done: string;
    active: string;
    upcoming: string;
    icon: typeof Package;
    label: string;
};

const STEP_CONFIG: Record<string, StepColors> = {
    pending: {
        done: 'bg-amber-500 border-amber-500 text-white',
        active: 'bg-amber-50 border-amber-500 text-amber-600',
        upcoming: 'bg-background border-border text-muted-foreground',
        icon: Clock,
        label: 'Pending',
    },
    processing: {
        done: 'bg-blue-500 border-blue-500 text-white',
        active: 'bg-blue-50 border-blue-500 text-blue-600',
        upcoming: 'bg-background border-border text-muted-foreground',
        icon: RotateCcw,
        label: 'Processing',
    },
    shipped: {
        done: 'bg-violet-500 border-violet-500 text-white',
        active: 'bg-violet-50 border-violet-500 text-violet-600',
        upcoming: 'bg-background border-border text-muted-foreground',
        icon: Truck,
        label: 'Shipped',
    },
    delivered: {
        done: 'bg-emerald-500 border-emerald-500 text-white',
        active: 'bg-emerald-50 border-emerald-500 text-emerald-600',
        upcoming: 'bg-background border-border text-muted-foreground',
        icon: CheckCircle,
        label: 'Delivered',
    },
};

const TIMELINE_ICON_COLORS: Record<string, string> = {
    pending: 'bg-amber-100 border-amber-400 text-amber-600',
    processing: 'bg-blue-100 border-blue-400 text-blue-600',
    shipped: 'bg-violet-100 border-violet-400 text-violet-600',
    delivered: 'bg-emerald-100 border-emerald-400 text-emerald-600',
    cancelled: 'bg-red-100 border-red-400 text-red-600',
};

const STATUS_BADGE_CLASSES: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-700 border border-amber-300',
    processing: 'bg-blue-100 text-blue-700 border border-blue-300',
    shipped: 'bg-violet-100 text-violet-700 border border-violet-300',
    delivered: 'bg-emerald-100 text-emerald-700 border border-emerald-300',
    cancelled: 'bg-red-100 text-red-700 border border-red-300',
};

const PAYMENT_BADGE_CLASSES: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-700 border border-amber-300',
    paid: 'bg-emerald-100 text-emerald-700 border border-emerald-300',
    failed: 'bg-red-100 text-red-700 border border-red-300',
    cancelled: 'bg-red-100 text-red-700 border border-red-300',
};

function stepState(step: OrderStatus): 'done' | 'active' | 'upcoming' {
    if (props.order.status === 'cancelled') {
        return 'upcoming';
    }

    const currentIndex = ORDER_STEPS.indexOf(props.order.status);
    const stepIndex = ORDER_STEPS.indexOf(step);

    if (stepIndex < currentIndex) {
        return 'done';
    }

    if (stepIndex === currentIndex) {
        return 'active';
    }

    return 'upcoming';
}

function stepClasses(step: OrderStatus): string {
    return STEP_CONFIG[step]?.[stepState(step)] ?? '';
}

function timelineIconClasses(status: string): string {
    return TIMELINE_ICON_COLORS[status] ?? 'bg-muted border-border text-muted-foreground';
}

function statusBadgeClasses(status: string): string {
    return STATUS_BADGE_CLASSES[status] ?? 'bg-muted text-foreground border';
}

function paymentBadgeClasses(status: string): string {
    return PAYMENT_BADGE_CLASSES[status] ?? 'bg-muted text-foreground border';
}

function paymentMethodLabel(method: AdminOrder['payment_method']): string {
    return method === 'cod' ? 'Cash on Delivery' : 'SSLCommerz';
}

function formatDate(value: string | null): string {
    if (!value) {
        return '—';
    }

    return new Date(value).toLocaleString('en-BD', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function printInvoice(): void {
    window.print();
}
</script>

<template>
    <Head :title="order.order_number" />

    <!-- ─── Screen view ─────────────────────────────────────────────────── -->
    <div
        id="order-screen"
        class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4"
    >
        <!-- Header bar -->
        <div class="flex flex-col gap-4 rounded-2xl bg-gradient-to-r from-violet-600 via-blue-600 to-indigo-600 p-5 text-white shadow-md sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-violet-200">
                    Order
                </p>
                <h1 class="text-2xl font-bold tracking-tight">
                    {{ order.order_number }}
                </h1>
                <p class="mt-0.5 text-sm text-violet-200">
                    Placed {{ formatDate(order.placed_at) }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span
                    class="rounded-full px-3 py-1 text-xs font-semibold capitalize"
                    :class="statusBadgeClasses(order.status)"
                    style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3)"
                >
                    {{ order.status }}
                </span>
                <span
                    class="rounded-full px-3 py-1 text-xs font-semibold capitalize"
                    style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3)"
                >
                    {{ order.payment_status }}
                </span>
                <Separator orientation="vertical" class="mx-1 h-6 bg-white/30" />
                <Button
                    variant="secondary"
                    size="sm"
                    class="bg-white/20 text-white hover:bg-white/30 border-white/30"
                    @click="printInvoice"
                >
                    <Printer class="size-4" />
                    Print
                </Button>
                <Button
                    variant="secondary"
                    size="sm"
                    class="bg-white/20 text-white hover:bg-white/30 border-white/30"
                    @click="printInvoice"
                >
                    <Download class="size-4" />
                    Save PDF
                </Button>
            </div>
        </div>

        <!-- Fulfillment stepper -->
        <Card v-if="order.status !== 'cancelled'" class="border-none shadow-sm bg-gradient-to-br from-slate-50 to-white dark:from-slate-900/40 dark:to-background">
            <CardContent class="pt-6 pb-5">
                <div class="relative flex items-start justify-between">
                    <!-- Background connector -->
                    <div
                        class="absolute top-5 right-0 left-0 mx-[calc(12.5%+20px)] h-0.5 bg-border"
                        aria-hidden="true"
                    />
                    <div
                        v-for="step in ORDER_STEPS"
                        :key="step"
                        class="relative flex flex-1 flex-col items-center gap-2"
                    >
                        <div
                            :class="[
                                'relative z-10 flex size-10 items-center justify-center rounded-full border-2 transition-all duration-300',
                                stepClasses(step),
                            ]"
                        >
                            <component :is="STEP_CONFIG[step].icon" class="size-4" />
                        </div>
                        <span
                            :class="[
                                'text-xs font-semibold capitalize',
                                stepState(step) === 'upcoming'
                                    ? 'text-muted-foreground'
                                    : 'text-foreground',
                            ]"
                        >
                            {{ STEP_CONFIG[step].label }}
                        </span>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Cancelled banner -->
        <div
            v-if="order.status === 'cancelled'"
            class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-400"
        >
            <XCircle class="size-5 shrink-0 text-red-500" />
            <p>
                This order has been <strong>cancelled</strong>.
                Payment status: <strong class="capitalize">{{ order.payment_status }}</strong>.
            </p>
        </div>

        <!-- Main content grid -->
        <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
            <!-- Left column -->
            <div class="grid auto-rows-min gap-6">
                <!-- Line items -->
                <Card class="overflow-hidden border-none shadow-sm">
                    <CardHeader class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30 border-b">
                        <CardTitle class="flex items-center gap-2 text-indigo-700 dark:text-indigo-400">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/50">
                                <Package class="size-4 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            Line items
                        </CardTitle>
                        <CardDescription>
                            {{ order.items_count }} {{ order.items_count === 1 ? 'item' : 'items' }} in this order
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="p-0">
                        <table class="w-full min-w-[540px] text-sm">
                            <thead class="bg-indigo-600 text-white">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold text-indigo-100 text-xs uppercase tracking-wide">Product</th>
                                    <th class="px-4 py-3 text-center font-semibold text-indigo-100 text-xs uppercase tracking-wide">Qty</th>
                                    <th class="px-4 py-3 text-right font-semibold text-indigo-100 text-xs uppercase tracking-wide">Unit price</th>
                                    <th class="px-5 py-3 text-right font-semibold text-indigo-100 text-xs uppercase tracking-wide">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, i) in order.items"
                                    :key="item.id"
                                    :class="['border-b last:border-b-0', i % 2 === 0 ? 'bg-white dark:bg-background' : 'bg-indigo-50/40 dark:bg-indigo-950/10']"
                                >
                                    <td class="px-5 py-4 font-medium">{{ item.product_name }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex size-7 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">
                                            {{ item.quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-muted-foreground">{{ formatTaka(item.unit_price) }}</td>
                                    <td class="px-5 py-4 text-right font-semibold">{{ formatTaka(item.line_total) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="border-t bg-slate-50 dark:bg-slate-900/30">
                                    <td colspan="3" class="px-5 py-2.5 text-right text-xs text-muted-foreground">Subtotal</td>
                                    <td class="px-5 py-2.5 text-right text-sm font-medium">{{ formatTaka(order.subtotal) }}</td>
                                </tr>
                                <tr class="bg-slate-50 dark:bg-slate-900/30">
                                    <td colspan="3" class="px-5 py-2.5 text-right text-xs text-muted-foreground">Delivery charge</td>
                                    <td class="px-5 py-2.5 text-right text-sm font-medium">{{ formatTaka(order.delivery_charge) }}</td>
                                </tr>
                                <tr class="bg-indigo-600">
                                    <td colspan="3" class="px-5 py-3.5 text-right text-sm font-bold text-indigo-100">Order total</td>
                                    <td class="px-5 py-3.5 text-right text-base font-extrabold text-white">{{ formatTaka(order.total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </CardContent>
                </Card>

                <!-- Status timeline -->
                <Card class="border-none shadow-sm">
                    <CardHeader class="bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-950/30 dark:to-purple-950/30 border-b">
                        <CardTitle class="flex items-center gap-2 text-violet-700 dark:text-violet-400">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/50">
                                <Clock class="size-4 text-violet-600 dark:text-violet-400" />
                            </div>
                            Status history
                        </CardTitle>
                        <CardDescription>Chronological log of all status changes</CardDescription>
                    </CardHeader>
                    <CardContent class="pt-6">
                        <div v-if="order.status_histories.length > 0" class="relative">
                            <div
                                class="absolute top-5 bottom-5 left-[19px] w-0.5 bg-gradient-to-b from-violet-300 via-blue-200 to-transparent"
                                aria-hidden="true"
                            />
                            <div
                                v-for="(history, i) in order.status_histories"
                                :key="history.id"
                                class="relative flex gap-4 pb-6 last:pb-0"
                            >
                                <div
                                    :class="[
                                        'relative z-10 mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-full border-2 transition-colors',
                                        i === 0 ? timelineIconClasses(history.status) : 'bg-muted/50 border-border text-muted-foreground',
                                    ]"
                                >
                                    <component :is="STEP_CONFIG[history.status]?.icon ?? Clock" class="size-4" />
                                </div>
                                <div class="flex flex-1 flex-col gap-1 pt-1.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize', statusBadgeClasses(history.status)]"
                                        >
                                            {{ history.status }}
                                        </span>
                                        <span class="text-xs text-muted-foreground">
                                            {{ formatDate(history.created_at) }}
                                        </span>
                                    </div>
                                    <p v-if="history.note" class="text-sm leading-relaxed">{{ history.note }}</p>
                                    <p v-if="history.changed_by" class="text-xs text-muted-foreground">
                                        Updated by
                                        <span class="font-medium text-foreground">{{ history.changed_by.name }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">
                            No status history yet.
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Right column -->
            <div class="grid auto-rows-min gap-6">
                <!-- Customer -->
                <Card class="border-none shadow-sm overflow-hidden">
                    <CardHeader class="bg-gradient-to-r from-sky-50 to-cyan-50 dark:from-sky-950/30 dark:to-cyan-950/30 border-b">
                        <CardTitle class="flex items-center gap-2 text-sky-700 dark:text-sky-400">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-sky-100 dark:bg-sky-900/50">
                                <User class="size-4 text-sky-600 dark:text-sky-400" />
                            </div>
                            Customer
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="grid gap-4 pt-5 text-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-400 to-cyan-500 text-white shadow-sm">
                                <User class="size-4" />
                            </div>
                            <div>
                                <p class="font-semibold">{{ order.customer_name }}</p>
                                <p class="text-xs text-muted-foreground">
                                    {{ order.customer ? 'Registered account' : 'Guest checkout' }}
                                </p>
                            </div>
                        </div>
                        <Separator />
                        <div class="flex items-center gap-3">
                            <div class="flex size-7 shrink-0 items-center justify-center rounded-md bg-sky-100 dark:bg-sky-900/40">
                                <Phone class="size-3.5 text-sky-600 dark:text-sky-400" />
                            </div>
                            <span>{{ order.phone }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex size-7 shrink-0 items-center justify-center rounded-md bg-sky-100 dark:bg-sky-900/40">
                                <Mail class="size-3.5 text-sky-600 dark:text-sky-400" />
                            </div>
                            <span class="break-all">{{ order.email }}</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Shipping -->
                <Card class="border-none shadow-sm overflow-hidden">
                    <CardHeader class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-950/30 dark:to-amber-950/30 border-b">
                        <CardTitle class="flex items-center gap-2 text-orange-700 dark:text-orange-400">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/50">
                                <MapPin class="size-4 text-orange-600 dark:text-orange-400" />
                            </div>
                            Shipping address
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="grid gap-3 pt-5 text-sm">
                        <div class="rounded-xl bg-orange-50/60 p-4 dark:bg-orange-950/20">
                            <p class="font-semibold leading-relaxed">
                                {{ order.address }},<br />
                                {{ order.area }}, {{ order.district }}
                            </p>
                        </div>
                        <div
                            v-if="order.notes"
                            class="rounded-xl border border-amber-200 bg-amber-50 p-3 dark:border-amber-900/40 dark:bg-amber-950/20"
                        >
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-400">
                                Delivery note
                            </p>
                            <p class="text-sm">{{ order.notes }}</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Payment summary -->
                <Card class="border-none shadow-sm overflow-hidden">
                    <CardHeader class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/30 border-b">
                        <CardTitle class="flex items-center gap-2 text-emerald-700 dark:text-emerald-400">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                <Package class="size-4 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            Payment summary
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="grid gap-3 pt-5 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">Method</span>
                            <span class="font-medium">{{ paymentMethodLabel(order.payment_method) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">Payment status</span>
                            <span
                                :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize', paymentBadgeClasses(order.payment_status)]"
                            >
                                {{ order.payment_status }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">Subtotal</span>
                            <span>{{ formatTaka(order.subtotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">Delivery charge</span>
                            <span>{{ formatTaka(order.delivery_charge) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-emerald-600 px-4 py-3 text-white">
                            <span class="font-semibold">Total</span>
                            <span class="text-lg font-extrabold">{{ formatTaka(order.total) }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs text-muted-foreground">
                            <div>
                                <p class="mb-0.5 font-medium">Placed</p>
                                <p>{{ formatDate(order.placed_at) }}</p>
                            </div>
                            <div>
                                <p class="mb-0.5 font-medium">Last updated</p>
                                <p>{{ formatDate(order.updated_at) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Update order -->
                <Card class="border-none shadow-sm overflow-hidden">
                    <CardHeader class="bg-gradient-to-r from-violet-50 to-indigo-50 dark:from-violet-950/30 dark:to-indigo-950/30 border-b">
                        <CardTitle class="flex items-center gap-2 text-violet-700 dark:text-violet-400">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/50">
                                <RotateCcw class="size-4 text-violet-600 dark:text-violet-400" />
                            </div>
                            Update order
                        </CardTitle>
                        <CardDescription>Change fulfillment or payment status</CardDescription>
                    </CardHeader>
                    <CardContent class="pt-5">
                        <form @submit.prevent="submit">
                            <OrderStatusForm
                                :form="form"
                                :status-options="statusOptions"
                                :payment-status-options="paymentStatusOptions"
                            />
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>

    <!-- ─── Printable Invoice (hidden on screen, shown on print) ───────── -->
    <div id="invoice-print">
        <!-- Coloured header bar -->
        <div class="invoice-header-bar">
            <div class="invoice-brand">
                <h1>B9-Ecom</h1>
                <p>Your trusted online store</p>
            </div>
            <div class="invoice-title-block">
                <h2>INVOICE</h2>
                <span class="invoice-number">{{ order.order_number }}</span>
            </div>
        </div>

        <!-- Meta row -->
        <div class="invoice-meta-row">
            <div class="invoice-meta-item">
                <span class="invoice-meta-label">Order Date</span>
                <span class="invoice-meta-value">{{ formatDate(order.placed_at) }}</span>
            </div>
            <div class="invoice-meta-item">
                <span class="invoice-meta-label">Payment Method</span>
                <span class="invoice-meta-value">{{ paymentMethodLabel(order.payment_method) }}</span>
            </div>
            <div class="invoice-meta-item">
                <span class="invoice-meta-label">Order Status</span>
                <span class="invoice-status-chip invoice-status-chip--order">{{ order.status }}</span>
            </div>
            <div class="invoice-meta-item">
                <span class="invoice-meta-label">Payment Status</span>
                <span class="invoice-status-chip invoice-status-chip--payment">{{ order.payment_status }}</span>
            </div>
        </div>

        <!-- Bill To / Ship To -->
        <div class="invoice-parties">
            <div class="invoice-party-box invoice-party-box--bill">
                <h3>Bill To</h3>
                <p><strong>{{ order.customer_name }}</strong></p>
                <p>{{ order.phone }}</p>
                <p>{{ order.email }}</p>
            </div>
            <div class="invoice-party-box invoice-party-box--ship">
                <h3>Ship To</h3>
                <p>{{ order.address }}</p>
                <p>{{ order.area }}, {{ order.district }}</p>
                <p v-if="order.notes">Note: {{ order.notes }}</p>
            </div>
        </div>

        <!-- Items table -->
        <table class="invoice-items">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(item, idx) in order.items"
                    :key="item.id"
                    :class="idx % 2 === 1 ? 'invoice-row-alt' : ''"
                >
                    <td>{{ idx + 1 }}</td>
                    <td>{{ item.product_name }}</td>
                    <td class="text-center">{{ item.quantity }}</td>
                    <td class="text-right">{{ formatTaka(item.unit_price) }}</td>
                    <td class="text-right">{{ formatTaka(item.line_total) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="invoice-subtotal-row">
                    <td colspan="4" class="text-right">Subtotal</td>
                    <td class="text-right">{{ formatTaka(order.subtotal) }}</td>
                </tr>
                <tr class="invoice-subtotal-row">
                    <td colspan="4" class="text-right">Delivery Charge</td>
                    <td class="text-right">{{ formatTaka(order.delivery_charge) }}</td>
                </tr>
                <tr class="invoice-total-row">
                    <td colspan="4" class="text-right">Grand Total</td>
                    <td class="text-right">{{ formatTaka(order.total) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="invoice-footer-bar">
            <p>Thank you for shopping with B9-Ecom! 🎉</p>
            <p>Questions? Email us at <strong>support@b9ecom.com</strong></p>
        </div>
    </div>
</template>

<style scoped>
#invoice-print {
    display: none;
}

@media print {
    #order-screen {
        display: none !important;
    }

    #invoice-print {
        display: block;
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 13px;
        color: #1e1e2e;
        max-width: 800px;
        margin: 0 auto;
    }

    /* ── Header bar ─────────────────────────────────────────── */
    .invoice-header-bar {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
        color: white;
        padding: 28px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 12px 12px 0 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-brand h1 {
        font-size: 28px;
        font-weight: 900;
        margin: 0 0 4px;
        color: white;
        letter-spacing: -0.5px;
    }

    .invoice-brand p {
        font-size: 12px;
        color: rgba(255,255,255,0.75);
        margin: 0;
    }

    .invoice-title-block {
        text-align: right;
    }

    .invoice-title-block h2 {
        font-size: 24px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 4px;
        color: rgba(255,255,255,0.85);
        margin: 0 0 6px;
    }

    .invoice-number {
        font-size: 15px;
        font-weight: 700;
        color: white;
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 1px;
    }

    /* ── Meta row ───────────────────────────────────────────── */
    .invoice-meta-row {
        display: flex;
        gap: 0;
        background: #f8f7ff;
        border: 1px solid #e0e0f0;
        border-top: none;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-meta-item {
        flex: 1;
        padding: 14px 16px;
        border-right: 1px solid #e0e0f0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .invoice-meta-item:last-child {
        border-right: none;
    }

    .invoice-meta-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #7c6faa;
    }

    .invoice-meta-value {
        font-size: 12px;
        font-weight: 600;
        color: #1e1e2e;
    }

    .invoice-status-chip {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
        padding: 2px 10px;
        border-radius: 20px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-status-chip--order {
        background: #ede9fe;
        color: #5b21b6;
    }

    .invoice-status-chip--payment {
        background: #d1fae5;
        color: #065f46;
    }

    /* ── Parties ────────────────────────────────────────────── */
    .invoice-parties {
        display: flex;
        gap: 0;
        margin: 20px 0;
        border: 1px solid #e0e0f0;
        border-radius: 10px;
        overflow: hidden;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-party-box {
        flex: 1;
        padding: 16px 20px;
    }

    .invoice-party-box--bill {
        background: #eff6ff;
        border-right: 1px solid #e0e0f0;
    }

    .invoice-party-box--ship {
        background: #fff7ed;
    }

    .invoice-party-box h3 {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6366f1;
        margin: 0 0 8px;
    }

    .invoice-party-box--ship h3 {
        color: #ea580c;
    }

    .invoice-party-box p {
        margin: 3px 0;
        font-size: 12px;
        color: #333;
        line-height: 1.6;
    }

    /* ── Items table ────────────────────────────────────────── */
    .invoice-items {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        border-radius: 10px;
        overflow: hidden;
    }

    .invoice-items thead tr {
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-items th {
        padding: 11px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #e0d9ff;
    }

    .invoice-items tbody tr {
        border-bottom: 1px solid #eeedf8;
    }

    .invoice-items td {
        padding: 10px 14px;
        color: #2e2e3e;
    }

    .invoice-row-alt {
        background: #f5f3ff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-subtotal-row td {
        padding: 7px 14px;
        color: #555;
        background: #fafafa;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-total-row {
        background: linear-gradient(90deg, #059669, #10b981);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-total-row td {
        padding: 14px 14px;
        font-size: 15px;
        font-weight: 800;
        color: white !important;
        background: transparent;
    }

    .text-left  { text-align: left; }
    .text-center{ text-align: center; }
    .text-right { text-align: right; }

    /* ── Footer bar ─────────────────────────────────────────── */
    .invoice-footer-bar {
        margin-top: 20px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        text-align: center;
        padding: 16px 24px;
        border-radius: 0 0 12px 12px;
        font-size: 12px;
        line-height: 1.8;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-footer-bar p {
        margin: 0;
        color: rgba(255,255,255,0.9);
    }

    .invoice-footer-bar strong {
        color: white;
    }
}
</style>
