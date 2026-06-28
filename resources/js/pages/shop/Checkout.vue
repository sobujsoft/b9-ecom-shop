<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import ShopCheckoutSummary from '@/components/shop/ShopCheckoutSummary.vue';
import ShopPageBreadcrumb from '@/components/shop/ShopPageBreadcrumb.vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopUi } from '@/composables/shop/useShopUi';
import type { ShopCheckoutConfig } from '@/types/shop';
import shop from '@/routes/shop';

const { districts, deliveryCharges } = defineProps<{
    districts: string[];
    deliveryCharges: ShopCheckoutConfig;
}>();

const { cart, cartSubtotal, updateQty, removeItem } = useShopCart();
const { showToast } = useShopUi();

const form = useForm({
    customer_name: '',
    phone: '',
    email: '',
    district: '',
    area: '',
    address: '',
    notes: '',
    payment_method: 'cod' as const,
});

const deliveryCharge = computed(() => {
    if (cart.value.length === 0) {
        return 0;
    }

    if (!form.district) {
        return deliveryCharges.outsideDhaka;
    }

    return form.district === deliveryCharges.dhakaDistrict
        ? deliveryCharges.insideDhaka
        : deliveryCharges.outsideDhaka;
});

const deliveryNote = computed(() => {
    if (cart.value.length === 0 || !form.district) {
        return '';
    }

    return form.district === deliveryCharges.dhakaDistrict
        ? '(Inside Dhaka)'
        : '(Outside Dhaka)';
});

function handleIncrement(productId: number): void {
    const item = cart.value.find((i) => i.productId === productId);
    if (item) {
        updateQty(productId, item.qty + 1);
    }
}

function handleDecrement(productId: number): void {
    const item = cart.value.find((i) => i.productId === productId);
    if (item && item.qty > 1) {
        updateQty(productId, item.qty - 1);
    }
}

function handleRemove(productId: number): void {
    const item = cart.value.find((i) => i.productId === productId);
    removeItem(productId);
    if (item) {
        showToast(`Removed: ${item.name}`);
    }
}

function handleSubmit(): void {
    if (cart.value.length === 0) {
        showToast('Your cart is empty');
        return;
    }

    form.post('/checkout', {
        preserveScroll: true,
        onError: () => {
            showToast('Please complete the highlighted fields');
        },
    });
}
</script>

<template>
    <Head title="Checkout">
        <meta
            name="description"
            content="Secure checkout with Cash on Delivery. Delivery across Bangladesh."
        />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="anonymous"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div class="bg-gray-50 py-6 md:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <ShopPageBreadcrumb
                :items="[
                    { label: 'Cart', href: shop.cart.url() },
                    { label: 'Checkout' },
                ]"
            />

            <form
                novalidate
                class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8"
                @submit.prevent="handleSubmit"
            >
                <div class="space-y-6 lg:col-span-2">
                    <section
                        class="rounded-xl border border-gray-200 bg-white p-5 md:p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900">
                            Shipping Details
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Where should we deliver your order?
                        </p>

                        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label
                                    for="customer_name"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Full name
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    id="customer_name"
                                    v-model="form.customer_name"
                                    name="customer_name"
                                    type="text"
                                    required
                                    autocomplete="name"
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        form.errors.customer_name
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="e.g. Rina Akter"
                                />
                                <p
                                    v-show="form.errors.customer_name"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.customer_name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="phone"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Phone
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    name="phone"
                                    type="tel"
                                    required
                                    autocomplete="tel"
                                    inputmode="numeric"
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        form.errors.phone
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="01XXXXXXXXX"
                                />
                                <p
                                    v-show="form.errors.phone"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    for="email"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Email
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    name="email"
                                    type="email"
                                    required
                                    autocomplete="email"
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        form.errors.email
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="you@example.com"
                                />
                                <p
                                    v-show="form.errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="district"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    District
                                    <span class="text-red-600">*</span>
                                </label>
                                <select
                                    id="district"
                                    v-model="form.district"
                                    name="district"
                                    required
                                    class="w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        form.errors.district
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                >
                                    <option value="">Select district</option>
                                    <option
                                        v-for="district in districts"
                                        :key="district"
                                        :value="district"
                                    >
                                        {{ district }}
                                    </option>
                                </select>
                                <p
                                    v-show="form.errors.district"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.district }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="area"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Area / Thana
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    id="area"
                                    v-model="form.area"
                                    name="area"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        form.errors.area
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="e.g. Dhanmondi"
                                />
                                <p
                                    v-show="form.errors.area"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.area }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    for="address"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Full address
                                    <span class="text-red-600">*</span>
                                </label>
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    name="address"
                                    rows="2"
                                    required
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        form.errors.address
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="House, road, and any landmark"
                                />
                                <p
                                    v-show="form.errors.address"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.address }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    for="notes"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Order notes
                                    <span class="text-gray-400">(optional)</span>
                                </label>
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    name="notes"
                                    rows="2"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-shop-primary-600 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    placeholder="Delivery instructions, preferred time, etc."
                                />
                            </div>
                        </div>
                    </section>

                    <section
                        class="rounded-xl border border-gray-200 bg-white p-5 md:p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900">
                            Payment Method
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Pay with cash when your order arrives.
                        </p>

                        <div
                            class="mt-5 flex items-start gap-3 rounded-xl border-2 border-shop-primary-600 bg-shop-primary-50 p-4"
                        >
                            <svg
                                class="mt-0.5 h-6 w-6 shrink-0 text-shop-primary-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h2m2-6h10a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6a2 2 0 012-2zm7 5a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Cash on Delivery
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    No online payment required. Pay the delivery
                                    agent in cash when you receive your order.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24">
                        <ShopCheckoutSummary
                            :items="cart"
                            :subtotal="cartSubtotal"
                            :delivery-charge="deliveryCharge"
                            :delivery-note="deliveryNote"
                            :is-empty="cart.length === 0"
                            :processing="form.processing"
                            @increment="handleIncrement"
                            @decrement="handleDecrement"
                            @remove="handleRemove"
                        />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
