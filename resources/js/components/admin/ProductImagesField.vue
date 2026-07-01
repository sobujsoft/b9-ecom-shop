<script setup lang="ts">
import { computed, onBeforeUnmount, reactive } from 'vue';
import type { InertiaForm } from '@inertiajs/vue3';
import { ArrowDown, ArrowUp, Plus, Trash2 } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    createEmptyProductImage,
    resolveImagePreview,
} from '@/lib/admin/productImages';
import type { ProductFormData } from '@/types/admin';

type Props = {
    form: InertiaForm<ProductFormData>;
};

const props = defineProps<Props>();

const filePreviewUrls = reactive<Record<string, string>>({});

const sortedImages = computed(() =>
    [...props.form.images].sort((a, b) => a.sort_order - b.sort_order),
);

function setFilePreview(clientKey: string, file: File | null): void {
    if (filePreviewUrls[clientKey]) {
        URL.revokeObjectURL(filePreviewUrls[clientKey]);
        delete filePreviewUrls[clientKey];
    }

    if (file) {
        filePreviewUrls[clientKey] = URL.createObjectURL(file);
    }
}

onBeforeUnmount(() => {
    Object.values(filePreviewUrls).forEach((url) => {
        URL.revokeObjectURL(url);
    });
});

function imageIndex(clientKey: string): number {
    return props.form.images.findIndex(
        (image) => image.client_key === clientKey,
    );
}

function previewForImage(clientKey: string): string | null {
    const image = props.form.images.find(
        (item) => item.client_key === clientKey,
    );

    if (!image) {
        return null;
    }

    return resolveImagePreview(image, filePreviewUrls[clientKey] ?? null);
}

function addUrlImage(): void {
    const isPrimary = props.form.images.length === 0;

    props.form.images.push(
        createEmptyProductImage(props.form.images.length, isPrimary),
    );
}

function onUploadFiles(event: Event): void {
    const target = event.target as HTMLInputElement;
    const files = Array.from(target.files ?? []);

    files.forEach((file, offset) => {
        const sortOrder = props.form.images.length + offset;
        const isPrimary = props.form.images.length === 0 && offset === 0;
        const clientKey = crypto.randomUUID();

        setFilePreview(clientKey, file);

        props.form.images.push({
            ...createEmptyProductImage(sortOrder, isPrimary),
            client_key: clientKey,
            source: 'upload',
            file,
        });
    });

    target.value = '';
}

function removeImage(clientKey: string): void {
    const index = imageIndex(clientKey);

    if (index === -1) {
        return;
    }

    const wasPrimary = props.form.images[index].is_primary;
    setFilePreview(clientKey, null);
    props.form.images.splice(index, 1);
    reindexImages();

    if (wasPrimary && props.form.images.length > 0) {
        props.form.images[0].is_primary = true;
    }
}

function setPrimary(clientKey: string): void {
    props.form.images.forEach((image) => {
        image.is_primary = image.client_key === clientKey;
    });
}

function moveImage(clientKey: string, direction: -1 | 1): void {
    const images = sortedImages.value;
    const currentIndex = images.findIndex(
        (image) => image.client_key === clientKey,
    );
    const targetIndex = currentIndex + direction;

    if (currentIndex === -1 || targetIndex < 0 || targetIndex >= images.length) {
        return;
    }

    const reordered = [...images];
    const [moved] = reordered.splice(currentIndex, 1);
    reordered.splice(targetIndex, 0, moved);
    reindexFromSorted(reordered);
}

function reindexImages(): void {
    reindexFromSorted(
        [...props.form.images].sort((a, b) => a.sort_order - b.sort_order),
    );
}

function reindexFromSorted(images: ProductFormData['images']): void {
    props.form.images = images.map((image, index) => ({
        ...image,
        sort_order: index,
    }));
}

function setImageSource(
    clientKey: string,
    source: ProductFormData['images'][number]['source'],
): void {
    const image = props.form.images.find((item) => item.client_key === clientKey);

    if (!image) {
        return;
    }

    image.source = source;
    image.file = null;
}

function onImageFileChange(clientKey: string, event: Event): void {
    const target = event.target as HTMLInputElement;
    const image = props.form.images.find((item) => item.client_key === clientKey);

    if (!image) {
        return;
    }

    image.file = target.files?.[0] ?? null;
    setFilePreview(clientKey, image.file);
}

function imageError(field: string, index: number): string | undefined {
    return props.form.errors[`images.${index}.${field}`];
}
</script>

<template>
    <section class="grid gap-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-medium text-muted-foreground">
                    Product images
                </h3>
                <p class="text-xs text-muted-foreground">
                    Add multiple images, choose the primary image, and set the
                    display order.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <Button type="button" variant="outline" size="sm" @click="addUrlImage">
                    <Plus class="size-4" />
                    Add URL image
                </Button>
                <Button type="button" variant="outline" size="sm" as-child>
                    <label class="cursor-pointer">
                        <Plus class="size-4" />
                        Upload images
                        <input
                            type="file"
                            class="sr-only"
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            multiple
                            @change="onUploadFiles"
                        />
                    </label>
                </Button>
            </div>
        </div>

        <InputError :message="form.errors.images" />

        <div v-if="sortedImages.length === 0" class="rounded-lg border border-dashed p-8 text-center text-sm text-muted-foreground">
            No images added yet. Add a URL image or upload files.
        </div>

        <div v-else class="grid gap-4">
            <div
                v-for="(image, displayIndex) in sortedImages"
                :key="image.client_key"
                class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
            >
                <div class="flex flex-col gap-4 lg:flex-row">
                    <div
                        class="size-28 shrink-0 overflow-hidden rounded-lg border bg-muted"
                    >
                        <img
                            v-if="previewForImage(image.client_key)"
                            :src="previewForImage(image.client_key)!"
                            :alt="image.alt_text || 'Product image preview'"
                            class="size-full object-cover"
                        />
                        <div
                            v-else
                            class="flex size-full items-center justify-center text-xs text-muted-foreground"
                        >
                            No preview
                        </div>
                    </div>

                    <div class="grid flex-1 gap-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <Badge variant="outline">
                                Order {{ displayIndex + 1 }}
                            </Badge>
                            <Badge v-if="image.is_primary">Primary</Badge>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                class="rounded-md border px-3 py-1.5 text-sm transition-colors"
                                :class="
                                    image.source === 'url'
                                        ? 'border-primary bg-primary text-primary-foreground'
                                        : 'border-input bg-background hover:bg-muted'
                                "
                                @click="setImageSource(image.client_key, 'url')"
                            >
                                URL
                            </button>
                            <button
                                type="button"
                                class="rounded-md border px-3 py-1.5 text-sm transition-colors"
                                :class="
                                    image.source === 'upload'
                                        ? 'border-primary bg-primary text-primary-foreground'
                                        : 'border-input bg-background hover:bg-muted'
                                "
                                @click="setImageSource(image.client_key, 'upload')"
                            >
                                Upload
                            </button>
                        </div>

                        <div v-if="image.source === 'url'" class="grid gap-2">
                            <Label :for="`image-path-${image.client_key}`">
                                Image URL or Unsplash ID
                            </Label>
                            <Input
                                :id="`image-path-${image.client_key}`"
                                v-model="image.image_path"
                                placeholder="https://example.com/image.jpg or photo-1505740420928-..."
                            />
                            <InputError
                                :message="
                                    imageError(
                                        'image_path',
                                        imageIndex(image.client_key),
                                    )
                                "
                            />
                        </div>

                        <div v-else class="grid gap-2">
                            <Label :for="`image-file-${image.client_key}`">
                                Image file
                            </Label>
                            <Input
                                :id="`image-file-${image.client_key}`"
                                type="file"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                @change="onImageFileChange(image.client_key, $event)"
                            />
                            <p class="text-xs text-muted-foreground">
                                JPG, PNG, or WebP up to 2 MB.
                                <span v-if="image.id">
                                    Leave empty to keep the current file.
                                </span>
                            </p>
                            <InputError
                                :message="
                                    imageError(
                                        'file',
                                        imageIndex(image.client_key),
                                    )
                                "
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label :for="`alt-text-${image.client_key}`">
                                Alt text
                            </Label>
                            <Input
                                :id="`alt-text-${image.client_key}`"
                                v-model="image.alt_text"
                                placeholder="Describe this image"
                            />
                            <InputError
                                :message="
                                    imageError(
                                        'alt_text',
                                        imageIndex(image.client_key),
                                    )
                                "
                            />
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                :disabled="displayIndex === 0"
                                @click="moveImage(image.client_key, -1)"
                            >
                                <ArrowUp class="size-4" />
                                Move up
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                :disabled="displayIndex === sortedImages.length - 1"
                                @click="moveImage(image.client_key, 1)"
                            >
                                <ArrowDown class="size-4" />
                                Move down
                            </Button>
                            <Button
                                type="button"
                                :variant="image.is_primary ? 'default' : 'outline'"
                                size="sm"
                                @click="setPrimary(image.client_key)"
                            >
                                Set as primary
                            </Button>
                            <Button
                                type="button"
                                variant="destructive"
                                size="sm"
                                @click="removeImage(image.client_key)"
                            >
                                <Trash2 class="size-4" />
                                Remove
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
