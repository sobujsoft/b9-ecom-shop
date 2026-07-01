<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import CategoryForm from '@/components/admin/CategoryForm.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/categories';
import type { CategoryFormData } from '@/types/admin';

defineOptions({
    layout: {
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
                title: 'Create',
                href: create(),
            },
        ],
    },
});

const form = useForm<CategoryFormData>({
    name: '',
    slug: '',
    image_source: 'url',
    image: '',
    image_file: null,
    description: '',
    sort_order: 0,
    is_active: true,
});

function submit(): void {
    form.post(CategoryController.store.url(), {
        forceFormData: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Create category" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            title="Create category"
            description="Add a new product category to your store"
        />

        <div
            class="max-w-2xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
        >
            <form class="space-y-6" @submit.prevent="submit">
                <CategoryForm :form="form" />

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="form.processing">
                        Create category
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="index()">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
