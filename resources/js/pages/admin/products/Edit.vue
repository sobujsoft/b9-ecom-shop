<script setup lang="ts">
import { Head, Link, setLayoutProps, useForm } from '@inertiajs/vue3';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import ProductForm from '@/components/admin/ProductForm.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { mapServerProductImages, serializeProductImagesForSubmit } from '@/lib/admin/productImages';
import { dashboard } from '@/routes';
import { edit, index } from '@/routes/admin/products';
import type {
    AdminCategoryOption,
    AdminProduct,
    ProductFormData,
} from '@/types/admin';

const props = defineProps<{
    product: AdminProduct;
    categories: AdminCategoryOption[];
}>();

setLayoutProps({
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
            title: `Edit ${props.product.name}`,
            href: edit(props.product.id),
        },
    ],
});

const form = useForm<ProductFormData>({
    category_id: props.product.category_id,
    name: props.product.name,
    slug: props.product.slug,
    short_description: props.product.short_description ?? '',
    description: props.product.description ?? '',
    price: props.product.price,
    compare_at_price: props.product.compare_at_price ?? '',
    stock_status: props.product.stock_status,
    is_best_seller: props.product.is_best_seller,
    is_featured: props.product.is_featured,
    is_active: props.product.is_active,
    sold_count: props.product.sold_count,
    images: mapServerProductImages(props.product.images),
});

function submit(): void {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
            images: serializeProductImagesForSubmit(data.images),
        }))
        .post(ProductController.update.url(props.product.id), {
            forceFormData: true,
            preserveScroll: true,
        });
}
</script>

<template>
    <Head :title="`Edit ${product.name}`" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            :title="`Edit ${product.name}`"
            description="Update product details"
        />

        <div
            class="max-w-3xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
        >
            <form class="space-y-6" @submit.prevent="submit">
                <ProductForm :form="form" :categories="categories" />

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="form.processing">
                        Save changes
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="index()">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
