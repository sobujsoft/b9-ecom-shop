import type {
    AdminProductImagePayload,
    ProductImageFormItem,
} from '@/types/admin';

export function createEmptyProductImage(
    sortOrder: number,
    isPrimary = false,
): ProductImageFormItem {
    return {
        id: null,
        client_key: crypto.randomUUID(),
        source: 'url',
        image_path: '',
        preview_url: null,
        alt_text: '',
        is_primary: isPrimary,
        sort_order: sortOrder,
        file: null,
    };
}

export function mapServerProductImages(
    images: AdminProductImagePayload[],
): ProductImageFormItem[] {
    return images.map((image) => ({
        id: image.id,
        client_key: `existing-${image.id}`,
        source: image.source,
        image_path: image.image_path,
        preview_url: image.preview,
        alt_text: image.alt_text ?? '',
        is_primary: image.is_primary,
        sort_order: image.sort_order,
        file: null,
    }));
}

export function resolveImagePreview(
    image: Pick<ProductImageFormItem, 'source' | 'image_path' | 'preview_url' | 'file'>,
    objectUrl?: string | null,
): string | null {
    if (image.source === 'upload') {
        return objectUrl ?? image.preview_url;
    }

    if (!image.image_path) {
        return image.preview_url;
    }

    if (image.image_path.startsWith('photo-')) {
        return `https://images.unsplash.com/${image.image_path}?auto=format&fit=crop&w=400&q=70`;
    }

    if (image.image_path.startsWith('http://') || image.image_path.startsWith('https://')) {
        return image.image_path;
    }

    return image.preview_url;
}

export function serializeProductImagesForSubmit(
    images: ProductImageFormItem[],
): Array<Omit<ProductImageFormItem, 'client_key' | 'preview_url'>> {
    return images.map((image, index) => ({
        id: image.id,
        source: image.source,
        image_path: image.image_path,
        alt_text: image.alt_text,
        is_primary: image.is_primary,
        sort_order: index,
        file: image.file,
    }));
}
