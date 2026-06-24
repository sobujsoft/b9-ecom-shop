<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ShopLogo from '@/components/shop/ShopLogo.vue';
import { useShopCart } from '@/composables/shop/useShopCart';
import { useShopCatalog } from '@/composables/shop/useShopCatalog';
import { useShopUi } from '@/composables/shop/useShopUi';
import { useShopWishlist } from '@/composables/shop/useShopWishlist';
import { home } from '@/routes';
import shop from '@/routes/shop';

const page = usePage();
const { openMobileMenu } = useShopUi();
const { cartQty } = useShopCart();
const { wishCount } = useShopWishlist();
const { search, setSearch } = useShopCatalog();

const isShopPage = computed(() => page.component === 'shop/Shop');
const isHomePage = computed(() => page.component === 'shop/Home');
const isCartPage = computed(() => page.component === 'shop/Cart');
const isWishlistPage = computed(() => page.component === 'shop/Wishlist');
const isCheckoutPage = computed(() => page.component === 'shop/Checkout');
const showMobileSearch = ref(false);

function toggleMobileSearch(): void {
    showMobileSearch.value = !showMobileSearch.value;
}

function handleSearchInput(event: Event): void {
    if (!isShopPage.value) {
        return;
    }

    setSearch((event.target as HTMLInputElement).value);
}
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        aria-label="Open menu"
                        aria-expanded="false"
                        aria-controls="mobileDrawer"
                        class="-ml-1 inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none lg:hidden"
                        @click="openMobileMenu"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>

                    <ShopLogo />
                </div>

                <nav
                    class="hidden items-center gap-1 lg:flex"
                    aria-label="Primary"
                >
                    <Link
                        :href="home()"
                        class="rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-gray-100"
                        :class="
                            isHomePage
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-600 hover:text-gray-900'
                        "
                    >
                        Home
                    </Link>
                    <Link
                        :href="shop.index()"
                        class="rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-gray-100"
                        :class="
                            isShopPage
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-600 hover:text-gray-900'
                        "
                    >
                        Shop
                    </Link>
                    <a
                        href="/#categories"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                    >
                        Categories
                    </a>
                    <a
                        href="/#bestselling"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                    >
                        Best Selling
                    </a>
                </nav>

                <div class="hidden max-w-md flex-1 md:block">
                    <label for="searchDesktop" class="sr-only"
                        >Search products</label
                    >
                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                />
                            </svg>
                        </span>
                        <input
                            id="searchDesktop"
                            type="search"
                            :value="isShopPage ? search : ''"
                            placeholder="Search for products…"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2.5 pr-4 pl-10 text-sm text-gray-900 transition placeholder:text-gray-400 focus:border-shop-primary-600 focus:bg-white focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                            @input="handleSearchInput"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <button
                        type="button"
                        aria-label="Search"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none md:hidden"
                        @click="toggleMobileSearch"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                            />
                        </svg>
                    </button>
                    <a
                        href="#"
                        aria-label="Account"
                        class="hidden h-11 w-11 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-shop-primary-600 focus:outline-none sm:inline-flex"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>
                    </a>
                    <Link
                        :href="shop.wishlist()"
                        :aria-label="`Wishlist, ${wishCount} items`"
                        class="relative inline-flex h-11 w-11 items-center justify-center rounded-lg focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        :class="
                            isWishlistPage
                                ? 'bg-red-50 text-red-600'
                                : 'text-gray-700 hover:bg-gray-100'
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                            />
                        </svg>
                        <span
                            class="absolute -top-0.5 -right-0.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-shop-accent-500 px-1 text-xs font-semibold text-white"
                        >
                            {{ wishCount }}
                        </span>
                    </Link>
                    <Link
                        :href="shop.cart()"
                        :aria-label="`Cart, ${cartQty} items`"
                        class="relative inline-flex h-11 w-11 items-center justify-center rounded-lg focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        :class="
                            isCartPage || isCheckoutPage
                                ? 'bg-shop-primary-50 text-shop-primary-600'
                                : 'text-gray-700 hover:bg-gray-100'
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                        <span
                            class="absolute -top-0.5 -right-0.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-shop-accent-500 px-1 text-xs font-semibold text-white"
                        >
                            {{ cartQty }}
                        </span>
                    </Link>
                </div>
            </div>

            <div
                v-show="showMobileSearch"
                class="pb-3 md:hidden"
            >
                <label for="searchMobileHeader" class="sr-only"
                    >Search products</label
                >
                <div class="relative">
                    <span
                        class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                            />
                        </svg>
                    </span>
                    <input
                        id="searchMobileHeader"
                        type="search"
                        :value="isShopPage ? search : ''"
                        placeholder="Search for products…"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2.5 pr-4 pl-10 text-sm focus:border-shop-primary-600 focus:bg-white focus:ring-2 focus:ring-shop-primary-600 focus:outline-none"
                        @input="handleSearchInput"
                    />
                </div>
            </div>
        </div>
    </header>
</template>
