<script setup lang="ts">
import { Head, Link, setLayoutProps } from '@inertiajs/vue3';
import { Pencil } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import RichTextContent from '@/components/RichTextContent.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { formatTaka } from '@/lib/shop/currency';
import { dashboard } from '@/routes';
import { edit, index, show } from '@/routes/admin/products';
import type { AdminProduct } from '@/types/admin';

const props = defineProps<{
    product: AdminProduct;
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
            title: props.product.name,
            href: show(props.product.id),
        },
    ],
});
</script>

<template>
    <Head :title="product.name" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <Heading :title="product.name" description="Product details" />

            <Button as-child>
                <Link :href="edit(product.id)">
                    <Pencil class="size-4" />
                    Edit product
                </Link>
            </Button>
        </div>

        <div class="grid gap-4 lg:grid-cols-[320px_1fr]">
            <Card>
                <CardHeader>
                    <CardTitle>Images</CardTitle>
                    <CardDescription>
                        Gallery ordered by sort position
                    </CardDescription>
                </CardHeader>
                <CardContent class="grid gap-3">
                    <div
                        v-if="product.images.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        No images uploaded.
                    </div>
                    <div
                        v-for="image in product.images"
                        :key="image.id"
                        class="overflow-hidden rounded-lg border"
                    >
                        <div class="aspect-square bg-muted">
                            <img
                                v-if="image.preview"
                                :src="image.preview"
                                :alt="image.alt_text || product.name"
                                class="size-full object-cover"
                            />
                        </div>
                        <div class="flex items-center justify-between gap-2 p-2 text-xs">
                            <span class="text-muted-foreground">
                                Order {{ image.sort_order + 1 }}
                            </span>
                            <Badge v-if="image.is_primary" variant="outline">
                                Primary
                            </Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Overview</CardTitle>
                    <CardDescription>
                        Catalog and pricing information
                    </CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4">
                    <div class="grid gap-1">
                        <p class="text-sm text-muted-foreground">Category</p>
                        <p class="font-medium">{{ product.category.name }}</p>
                    </div>

                    <div class="grid gap-1">
                        <p class="text-sm text-muted-foreground">Slug</p>
                        <p class="font-medium">{{ product.slug }}</p>
                    </div>

                    <div class="grid gap-1">
                        <p class="text-sm text-muted-foreground">
                            Short description
                        </p>
                        <p class="font-medium">
                            {{
                                product.short_description ||
                                'No short description.'
                            }}
                        </p>
                    </div>

                    <div class="grid gap-1">
                        <p class="text-sm text-muted-foreground">
                            Description
                        </p>
                        <RichTextContent :html="product.description ?? ''" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="grid gap-1">
                            <p class="text-sm text-muted-foreground">Price</p>
                            <p class="font-medium">
                                {{ formatTaka(product.price) }}
                            </p>
                        </div>
                        <div class="grid gap-1">
                            <p class="text-sm text-muted-foreground">
                                Compare at
                            </p>
                            <p class="font-medium">
                                {{
                                    product.compare_at_price
                                        ? formatTaka(product.compare_at_price)
                                        : '—'
                                }}
                            </p>
                        </div>
                        <div class="grid gap-1">
                            <p class="text-sm text-muted-foreground">Sold</p>
                            <p class="font-medium">{{ product.sold_count }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <Badge
                            :variant="
                                product.stock_status === 'in_stock'
                                    ? 'default'
                                    : 'secondary'
                            "
                        >
                            {{
                                product.stock_status === 'in_stock'
                                    ? 'In stock'
                                    : 'Out of stock'
                            }}
                        </Badge>
                        <Badge
                            :variant="
                                product.is_active ? 'default' : 'secondary'
                            "
                        >
                            {{ product.is_active ? 'Active' : 'Inactive' }}
                        </Badge>
                        <Badge v-if="product.is_featured" variant="outline">
                            Featured
                        </Badge>
                        <Badge v-if="product.is_best_seller" variant="outline">
                            Best seller
                        </Badge>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-1">
                            <p class="text-sm text-muted-foreground">
                                Created
                            </p>
                            <p class="font-medium">
                                {{
                                    new Date(
                                        product.created_at,
                                    ).toLocaleString()
                                }}
                            </p>
                        </div>
                        <div class="grid gap-1">
                            <p class="text-sm text-muted-foreground">
                                Updated
                            </p>
                            <p class="font-medium">
                                {{
                                    new Date(
                                        product.updated_at,
                                    ).toLocaleString()
                                }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
