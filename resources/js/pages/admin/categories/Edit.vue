<script setup lang="ts">
import { Head, Link, setLayoutProps, useForm } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import CategoryForm from '@/components/admin/CategoryForm.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index } from '@/routes/admin/categories';
import type { AdminCategory, CategoryFormData } from '@/types/admin';

const props = defineProps<{
    category: AdminCategory;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
        {
            title: 'Categories',
            href: index(),
        },
        {
            title: `Edit ${props.category.name}`,
            href: edit(props.category.id),
        },
    ],
});

const form = useForm<CategoryFormData>({
    name: props.category.name,
    slug: props.category.slug,
    image_source: props.category.image_source,
    image: props.category.image_url,
    image_file: null,
    description: props.category.description ?? '',
    sort_order: props.category.sort_order,
    is_active: props.category.is_active,
});

function submit(): void {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(CategoryController.update.url(props.category.id), {
            forceFormData: true,
            preserveScroll: true,
        });
}
</script>

<template>
    <Head :title="`Edit ${category.name}`" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            :title="`Edit ${category.name}`"
            description="Update category details"
        />

        <div
            class="max-w-2xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
        >
            <form class="space-y-6" @submit.prevent="submit">
                <CategoryForm :form="form" :category="category" />

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
