<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';
import ShopCheckoutSummary from '@/components/shop/ShopCheckoutSummary.vue';
import ShopPageBreadcrumb from '@/components/shop/ShopPageBreadcrumb.vue';
import {
    bangladeshDistricts,
    deliveryCharges,
} from '@/data/shop/cart-checkout';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopOrder } from '@/composables/shop/useShopOrder';
import { useShopUi } from '@/composables/shop/useShopUi';
import { formatTaka } from '@/lib/shop/currency';
import shop from '@/routes/shop';

type PaymentMethod = 'cod' | 'sslcommerz';

type FormFields = {
    fullName: string;
    phone: string;
    email: string;
    district: string;
    area: string;
    address: string;
    notes: string;
};

type FormErrors = Record<keyof Omit<FormFields, 'notes'>, boolean>;

const {
    cart,
    cartSubtotal,
    incrementQty,
    decrementQty,
    removeItem,
    clearCart,
} = useShopCart();
const { showToast } = useShopUi();
const { setLastOrder } = useShopOrder();

const paymentMethod = ref<PaymentMethod>('cod');

const form = reactive<FormFields>({
    fullName: '',
    phone: '',
    email: '',
    district: '',
    area: '',
    address: '',
    notes: '',
});

const errors = reactive<FormErrors>({
    fullName: false,
    phone: false,
    email: false,
    district: false,
    area: false,
    address: false,
});

const deliveryCharge = computed(() => {
    if (cart.value.length === 0) {
        return 0;
    }

    if (!form.district) {
        return deliveryCharges.outside;
    }

    return form.district === 'Dhaka'
        ? deliveryCharges.dhaka
        : deliveryCharges.outside;
});

const deliveryNote = computed(() => {
    if (cart.value.length === 0 || !form.district) {
        return '';
    }

    return form.district === 'Dhaka'
        ? '(Inside Dhaka)'
        : '(Outside Dhaka)';
});

const grandTotal = computed(() => cartSubtotal.value + deliveryCharge.value);

function clearFieldError(field: keyof FormErrors): void {
    errors[field] = false;
}

function setFieldError(field: keyof FormErrors, hasError: boolean): void {
    errors[field] = hasError;
}

function validateForm(): boolean {
    let isValid = true;
    let firstInvalidField: HTMLElement | undefined;

    const requiredFields: (keyof FormErrors)[] = [
        'fullName',
        'phone',
        'email',
        'district',
        'area',
        'address',
    ];

    requiredFields.forEach((field) => {
        let hasError = !form[field].trim();

        if (field === 'phone' && form.phone.trim()) {
            hasError = !/^01\d{9}$/.test(
                form.phone.replace(/\s|-/g, ''),
            );
        }

        if (field === 'email' && form.email.trim()) {
            hasError = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email);
        }

        setFieldError(field, hasError);

        if (hasError) {
            isValid = false;

            if (!firstInvalidField) {
                firstInvalidField =
                    document.getElementById(field) ?? undefined;
            }
        }
    });

    firstInvalidField?.scrollIntoView({ behavior: 'smooth', block: 'center' });

    return isValid;
}

function handleRemove(index: number): void {
    const removed = removeItem(index);

    if (removed) {
        showToast(`Removed: ${removed.name}`);
    }
}

function handleSubmit(event: Event): void {
    event.preventDefault();

    if (cart.value.length === 0) {
        showToast('Your cart is empty');

        return;
    }

    if (!validateForm()) {
        showToast('Please complete the highlighted fields');

        return;
    }

    if (paymentMethod.value === 'sslcommerz') {
        showToast('Redirecting to SSLCommerz…');
    }

    const placeOrder = (): void => {
        setLastOrder({
            orderNumber: `SE-${new Date().getFullYear()}-${Math.floor(100000 + Math.random() * 900000)}`,
            total: formatTaka(grandTotal.value),
            paymentLabel:
                paymentMethod.value === 'cod'
                    ? 'Cash on Delivery'
                    : 'SSLCommerz (Paid)',
        });
        clearCart();
        router.visit(shop.orders.success());
    };

    if (paymentMethod.value === 'sslcommerz') {
        window.setTimeout(placeOrder, 900);
    } else {
        placeOrder();
    }
}
</script>

<template>
    <Head title="Checkout">
        <meta
            name="description"
            content="Secure checkout — Cash on Delivery or SSLCommerz. Delivery across Bangladesh."
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
                @submit="handleSubmit"
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
                                    for="fullName"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    Full name
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    id="fullName"
                                    v-model="form.fullName"
                                    name="fullName"
                                    type="text"
                                    required
                                    autocomplete="name"
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                                    :class="
                                        errors.fullName
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="e.g. Rina Akter"
                                    @input="clearFieldError('fullName')"
                                />
                                <p
                                    v-show="errors.fullName"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    Please enter your name.
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
                                        errors.phone
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="01XXXXXXXXX"
                                    @input="clearFieldError('phone')"
                                />
                                <p
                                    v-show="errors.phone"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    Enter a valid 11-digit phone number.
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
                                        errors.email
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="you@example.com"
                                    @input="clearFieldError('email')"
                                />
                                <p
                                    v-show="errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    Enter a valid email address.
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
                                        errors.district
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    @change="clearFieldError('district')"
                                >
                                    <option value="">Select district</option>
                                    <option
                                        v-for="district in bangladeshDistricts"
                                        :key="district"
                                        :value="district"
                                    >
                                        {{ district }}
                                    </option>
                                </select>
                                <p
                                    v-show="errors.district"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    Please select your district.
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
                                        errors.area
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="e.g. Dhanmondi"
                                    @input="clearFieldError('area')"
                                />
                                <p
                                    v-show="errors.area"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    Please enter your area.
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
                                        errors.address
                                            ? 'border-red-500 focus:ring-red-500'
                                            : 'border-gray-300 focus:border-shop-primary-600'
                                    "
                                    placeholder="House, road, and any landmark"
                                    @input="clearFieldError('address')"
                                />
                                <p
                                    v-show="errors.address"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    Please enter your address.
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
                            Choose how you'd like to pay.
                        </p>

                        <div class="mt-5 space-y-3">
                            <label
                                class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-4 transition"
                                :class="
                                    paymentMethod === 'cod'
                                        ? 'border-shop-primary-600 bg-shop-primary-50'
                                        : 'border-gray-200 hover:border-gray-300'
                                "
                            >
                                <input
                                    v-model="paymentMethod"
                                    type="radio"
                                    name="payment"
                                    value="cod"
                                    class="mt-0.5 h-4 w-4 text-shop-primary-600 focus:ring-shop-primary-600"
                                />
                                <span class="flex-1">
                                    <span
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm font-semibold text-gray-900"
                                            >Cash on Delivery</span
                                        >
                                        <svg
                                            class="h-6 w-6 text-shop-primary-600"
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
                                    </span>
                                    <span
                                        class="mt-1 block text-sm text-gray-500"
                                    >
                                        Pay with cash when your order arrives.
                                    </span>
                                </span>
                            </label>

                            <label
                                class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-4 transition"
                                :class="
                                    paymentMethod === 'sslcommerz'
                                        ? 'border-shop-primary-600 bg-shop-primary-50'
                                        : 'border-gray-200 hover:border-gray-300'
                                "
                            >
                                <input
                                    v-model="paymentMethod"
                                    type="radio"
                                    name="payment"
                                    value="sslcommerz"
                                    class="mt-0.5 h-4 w-4 text-shop-primary-600 focus:ring-shop-primary-600"
                                />
                                <span class="flex-1">
                                    <span
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm font-semibold text-gray-900"
                                            >Pay Online (SSLCommerz)</span
                                        >
                                        <svg
                                            class="h-6 w-6 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                            />
                                        </svg>
                                    </span>
                                    <span
                                        class="mt-1 block text-sm text-gray-500"
                                    >
                                        Card, bKash, Nagad, Rocket & more via
                                        secure gateway.
                                    </span>
                                    <span class="mt-2 flex flex-wrap gap-1.5">
                                        <span
                                            class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600"
                                            >VISA</span
                                        >
                                        <span
                                            class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600"
                                            >Mastercard</span
                                        >
                                        <span
                                            class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600"
                                            >bKash</span
                                        >
                                        <span
                                            class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600"
                                            >Nagad</span
                                        >
                                    </span>
                                </span>
                            </label>
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
                            :payment-method="paymentMethod"
                            :is-empty="cart.length === 0"
                            @increment="incrementQty"
                            @decrement="decrementQty"
                            @remove="handleRemove"
                        />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
