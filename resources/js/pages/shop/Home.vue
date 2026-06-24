<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ShopCategoryGrid from '@/components/shop/ShopCategoryGrid.vue';
import ShopHeroCarousel from '@/components/shop/ShopHeroCarousel.vue';
import ShopNewsletter from '@/components/shop/ShopNewsletter.vue';
import ShopProductSection from '@/components/shop/ShopProductSection.vue';
import ShopTrustStrip from '@/components/shop/ShopTrustStrip.vue';
import {
    bestSellingProducts,
    newCollectionProducts,
} from '@/data/shop/static';
</script>

<template>
    <Head title="Home">
        <meta
            name="description"
            content="ShopEase — shop the best of electronics, fashion, home & more. Cash on Delivery and online payment across Bangladesh."
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

    <ShopHeroCarousel />
    <ShopTrustStrip />
    <ShopCategoryGrid />
    <ShopProductSection
        id="bestselling"
        title="Best selling"
        description="Loved most by our customers this month."
        :products="bestSellingProducts"
        background="gray"
    />
    <ShopProductSection
        id="newcollection"
        title="New collection"
        description="Just landed — be the first to grab them."
        :products="newCollectionProducts"
    />
    <ShopNewsletter />
</template>
