<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import ShopMobileProductBar from '@/components/shop/ShopMobileProductBar.vue';
import ShopProductBreadcrumb from '@/components/shop/ShopProductBreadcrumb.vue';
import ShopProductGallery from '@/components/shop/ShopProductGallery.vue';
import ShopProductInfo from '@/components/shop/ShopProductInfo.vue';
import ShopProductTabs from '@/components/shop/ShopProductTabs.vue';
import ShopRelatedProducts from '@/components/shop/ShopRelatedProducts.vue';
import type { ShopProduct, ShopProductDetail } from '@/types/shop';

const page = usePage();

const product = computed(
    () => page.props.product as ShopProductDetail | undefined,
);
const relatedProducts = computed(
    () => (page.props.relatedProducts as ShopProduct[] | undefined) ?? [],
);

const quantity = ref(1);
</script>

<template>
    <template v-if="product">
        <Head :title="product.name">
            <meta
                name="description"
                :content="`${product.name}. ${product.summary}`"
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

        <div class="pb-24 lg:pb-0">
            <ShopProductBreadcrumb
                :category="product.category"
                :category-href="product.categoryHref"
                :product-name="product.name"
            />

            <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 md:py-12 lg:px-8">
                <div
                    class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-start lg:gap-12"
                >
                    <ShopProductGallery
                        :images="product.images"
                        :alt="product.name"
                    />
                    <ShopProductInfo
                        :product="product"
                        v-model:quantity="quantity"
                    />
                </div>
            </section>

            <ShopProductTabs :product="product" />
            <ShopRelatedProducts
                v-if="relatedProducts.length > 0"
                :products="relatedProducts"
            />
        </div>

        <ShopMobileProductBar
            :product="product"
            v-model:quantity="quantity"
        />
    </template>
</template>
