<script setup lang="ts">
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import type { InertiaForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { AdminCategory, CategoryFormData } from '@/types/admin';

type Props = {
    form: InertiaForm<CategoryFormData>;
    category?: AdminCategory;
};

const props = defineProps<Props>();

const inputClass =
    'border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex min-h-24 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50';

const filePreviewUrl = ref<string | null>(null);

const previewUrl = computed(() => {
    if (props.form.image_source === 'upload' && filePreviewUrl.value) {
        return filePreviewUrl.value;
    }

    if (props.form.image_source === 'url' && props.form.image) {
        return props.form.image;
    }

    return props.category?.image ?? null;
});

watch(
    () => props.form.image_file,
    (file) => {
        if (filePreviewUrl.value) {
            URL.revokeObjectURL(filePreviewUrl.value);
            filePreviewUrl.value = null;
        }

        if (file) {
            filePreviewUrl.value = URL.createObjectURL(file);
        }
    },
);

onBeforeUnmount(() => {
    if (filePreviewUrl.value) {
        URL.revokeObjectURL(filePreviewUrl.value);
    }
});

function onFileChange(event: Event): void {
    const target = event.target as HTMLInputElement;
    props.form.image_file = target.files?.[0] ?? null;
}

function setImageSource(source: CategoryFormData['image_source']): void {
    props.form.image_source = source;
}
</script>

<template>
    <div class="grid gap-6">
        <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input
                id="name"
                v-model="form.name"
                required
                placeholder="Category name"
            />
            <InputError :message="form.errors.name" />
        </div>

        <div class="grid gap-2">
            <Label for="slug">Slug</Label>
            <Input
                id="slug"
                v-model="form.slug"
                placeholder="category-slug"
            />
            <p class="text-xs text-muted-foreground">
                Leave blank to generate from the name.
            </p>
            <InputError :message="form.errors.slug" />
        </div>

        <div class="grid gap-4">
            <div class="grid gap-2">
                <Label>Image</Label>
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="rounded-md border px-3 py-1.5 text-sm transition-colors"
                        :class="
                            form.image_source === 'url'
                                ? 'border-primary bg-primary text-primary-foreground'
                                : 'border-input bg-background hover:bg-muted'
                        "
                        @click="setImageSource('url')"
                    >
                        Image URL
                    </button>
                    <button
                        type="button"
                        class="rounded-md border px-3 py-1.5 text-sm transition-colors"
                        :class="
                            form.image_source === 'upload'
                                ? 'border-primary bg-primary text-primary-foreground'
                                : 'border-input bg-background hover:bg-muted'
                        "
                        @click="setImageSource('upload')"
                    >
                        Upload image
                    </button>
                </div>
                <InputError :message="form.errors.image_source" />
            </div>

            <div v-if="form.image_source === 'url'" class="grid gap-2">
                <Label for="image">Image URL</Label>
                <Input
                    id="image"
                    v-model="form.image"
                    type="url"
                    placeholder="https://example.com/image.jpg"
                />
                <InputError :message="form.errors.image" />
            </div>

            <div v-else class="grid gap-2">
                <Label for="image_file">Upload file</Label>
                <Input
                    id="image_file"
                    type="file"
                    accept="image/jpeg,image/jpg,image/png,image/webp"
                    @change="onFileChange"
                />
                <p class="text-xs text-muted-foreground">
                    JPG, PNG, or WebP up to 2 MB.
                    <span v-if="category?.image_source === 'upload'">
                        Leave empty to keep the current image.
                    </span>
                </p>
                <InputError :message="form.errors.image_file" />
            </div>

            <div
                v-if="previewUrl"
                class="size-32 overflow-hidden rounded-lg border bg-muted"
            >
                <img
                    :src="previewUrl"
                    alt="Category image preview"
                    class="size-full object-cover"
                />
            </div>
        </div>

        <div class="grid gap-2">
            <Label for="description">Description</Label>
            <textarea
                id="description"
                v-model="form.description"
                rows="4"
                :class="inputClass"
                placeholder="Short description for this category"
            />
            <InputError :message="form.errors.description" />
        </div>

        <div class="grid gap-2 sm:max-w-xs">
            <Label for="sort_order">Sort order</Label>
            <Input
                id="sort_order"
                v-model.number="form.sort_order"
                type="number"
                min="0"
                required
            />
            <InputError :message="form.errors.sort_order" />
        </div>

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
            <InputError :message="form.errors.is_active" />
        </div>
    </div>
</template>
