<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import ProductForm from '@/components/admin/ProductForm.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { serializeProductImagesForSubmit } from '@/lib/admin/productImages';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/products';
import type { AdminCategoryOption, ProductFormData } from '@/types/admin';

defineProps<{
    categories: AdminCategoryOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Products',
                href: index(),
            },
            {
                title: 'Create',
                href: create(),
            },
        ],
    },
});

const form = useForm<ProductFormData>({
    category_id: '',
    name: '',
    slug: '',
    short_description: '',
    description: '',
    price: '',
    compare_at_price: '',
    stock_status: 'in_stock',
    is_best_seller: false,
    is_featured: false,
    is_active: true,
    sold_count: 0,
    images: [],
});

function submit(): void {
    form
        .transform((data) => ({
            ...data,
            images: serializeProductImagesForSubmit(data.images),
        }))
        .post(ProductController.store.url(), {
            forceFormData: true,
            preserveScroll: true,
        });
}
</script>

<template>
    <Head title="Create product" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            title="Create product"
            description="Add a new product to your catalog"
        />

        <div
            class="max-w-3xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
        >
            <form class="space-y-6" @submit.prevent="submit">
                <ProductForm :form="form" :categories="categories" />

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="form.processing">
                        Create product
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="index()">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
