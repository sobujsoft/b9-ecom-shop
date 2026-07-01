<script setup lang="ts">
import type { InertiaForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import ProductImagesField from '@/components/admin/ProductImagesField.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { AdminCategoryOption, ProductFormData } from '@/types/admin';

type Props = {
    form: InertiaForm<ProductFormData>;
    categories: AdminCategoryOption[];
};

defineProps<Props>();

const inputClass =
    'border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex min-h-24 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50';

const selectClass =
    'border-input bg-background ring-offset-background focus-visible:ring-ring flex h-10 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50';
</script>

<template>
    <div class="grid gap-8">
        <section class="grid gap-6">
            <h3 class="text-sm font-medium text-muted-foreground">
                Basic information
            </h3>

            <div class="grid gap-2">
                <Label for="category_id">Category</Label>
                <select
                    id="category_id"
                    v-model="form.category_id"
                    :class="selectClass"
                    required
                >
                    <option disabled value="">Select a category</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
                <InputError :message="form.errors.category_id" />
            </div>

            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    required
                    placeholder="Product name"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="slug">Slug</Label>
                <Input
                    id="slug"
                    v-model="form.slug"
                    placeholder="product-slug"
                />
                <p class="text-xs text-muted-foreground">
                    Leave blank to generate from the name.
                </p>
                <InputError :message="form.errors.slug" />
            </div>

            <div class="grid gap-2">
                <Label for="short_description">Short description</Label>
                <textarea
                    id="short_description"
                    v-model="form.short_description"
                    rows="2"
                    :class="inputClass"
                    placeholder="Brief summary shown on product cards"
                />
                <InputError :message="form.errors.short_description" />
            </div>

            <div class="grid gap-2">
                <Label>Description</Label>
                <RichTextEditor
                    v-model="form.description"
                    placeholder="Write a detailed product description with formatting"
                />
                <InputError :message="form.errors.description" />
            </div>
        </section>

        <section class="grid gap-6">
            <h3 class="text-sm font-medium text-muted-foreground">
                Pricing & inventory
            </h3>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="price">Price</Label>
                    <Input
                        id="price"
                        v-model="form.price"
                        type="number"
                        min="0"
                        step="0.01"
                        required
                    />
                    <InputError :message="form.errors.price" />
                </div>

                <div class="grid gap-2">
                    <Label for="compare_at_price">Compare at price</Label>
                    <Input
                        id="compare_at_price"
                        v-model="form.compare_at_price"
                        type="number"
                        min="0"
                        step="0.01"
                        placeholder="Optional"
                    />
                    <InputError :message="form.errors.compare_at_price" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="stock_status">Stock status</Label>
                    <select
                        id="stock_status"
                        v-model="form.stock_status"
                        :class="selectClass"
                        required
                    >
                        <option value="in_stock">In stock</option>
                        <option value="stock_out">Out of stock</option>
                    </select>
                    <InputError :message="form.errors.stock_status" />
                </div>

                <div class="grid gap-2 sm:max-w-xs">
                    <Label for="sold_count">Sold count</Label>
                    <Input
                        id="sold_count"
                        v-model.number="form.sold_count"
                        type="number"
                        min="0"
                        required
                    />
                    <InputError :message="form.errors.sold_count" />
                </div>
            </div>
        </section>

        <ProductImagesField :form="form" />

        <section class="grid gap-4">
            <h3 class="text-sm font-medium text-muted-foreground">Flags</h3>

            <div class="flex flex-wrap gap-6">
                <div class="flex items-center gap-3">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="size-4 rounded border border-input"
                    />
                    <Label for="is_active" class="cursor-pointer font-normal">
                        Active
                    </Label>
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="is_featured"
                        v-model="form.is_featured"
                        type="checkbox"
                        class="size-4 rounded border border-input"
                    />
                    <Label for="is_featured" class="cursor-pointer font-normal">
                        Featured
                    </Label>
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="is_best_seller"
                        v-model="form.is_best_seller"
                        type="checkbox"
                        class="size-4 rounded border border-input"
                    />
                    <Label
                        for="is_best_seller"
                        class="cursor-pointer font-normal"
                    >
                        Best seller
                    </Label>
                </div>
            </div>

            <InputError :message="form.errors.is_active" />
            <InputError :message="form.errors.is_featured" />
            <InputError :message="form.errors.is_best_seller" />
        </section>
    </div>
</template>
