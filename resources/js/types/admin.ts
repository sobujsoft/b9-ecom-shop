export type CategoryImageSource = 'url' | 'upload';

export type AdminCategory = {
    id: number;
    name: string;
    slug: string;
    image: string | null;
    image_source: CategoryImageSource;
    image_url: string;
    description: string | null;
    sort_order: number;
    is_active: boolean;
    products_count: number;
    created_at: string;
    updated_at: string;
};

export type CategoryFormData = {
    name: string;
    slug: string;
    image_source: CategoryImageSource;
    image: string;
    image_file: File | null;
    description: string;
    sort_order: number;
    is_active: boolean;
};
